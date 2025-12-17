<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Transaksi; // Pastikan model ini ada
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Exception;

class PembeliController extends Controller
{
    // 1. Halaman Menu
    public function index()
    {
        // Mengambil produk termasuk yang non-aktif (untuk ditampilkan sebagai habis/tidak tersedia)
        $produk = Produk::with('kategori')->get(); 
        $kategori = Kategori::all(); 
        
        return view('pembeli.index', compact('produk', 'kategori'));
    }

    // 2. Tambah ke Keranjang
    public function addToCart(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        
        // Security Layer: Cek ketersediaan
        if($produk->status == 'non-aktif' || $produk->stock <= 0) {
            return redirect()->back()->with('error', 'Maaf, produk ini sedang tidak tersedia.');
        }

        $cart = session()->get('cart', []);
        
        $qty = (int) $request->input('quantity', 1);
        if($qty < 1) $qty = 1;

        $notes = [];
        $biaya_tambahan = 0;

        // --- A. LOGIKA MINUMAN & DESSERT ---
        if($request->filled('opsi_es')) { 
            $notes[] = $request->input('opsi_es'); 
        }
        if($request->filled('opsi_susu')) {
            $notes[] = $request->input('opsi_susu'); 
        }
        
        // Buah (+2000 per item)
        if($request->has('buah')) {
            $buah_pilihan = $request->input('buah');
            if(is_array($buah_pilihan)){
                $jumlah_buah = count($buah_pilihan);
                $biaya_tambahan += ($jumlah_buah * 2000);
                $buahList = implode(', ', $buah_pilihan);
                $notes[] = "+Buah: $buahList";
            }
        }

        // Jelly (+2000)
        if($request->has('extra_jelly')) { 
            $biaya_tambahan += 2000; 
            $notes[] = "+Jelly"; 
        }

        // --- B. LOGIKA MAKANAN BERAT ---
        $cabe = $request->input('cabe');
        if($cabe && $cabe > 0) { $notes[] = "Cabe: $cabe"; }
        
        if($request->has('extra_lontong')) { 
            $biaya_tambahan += 3000;
            $notes[] = "+Extra Lontong"; 
        }
        if($request->has('tambah_telur')) { 
            $biaya_tambahan += 4000;
            $notes[] = "+Telur"; 
        }
        
        // Opsi "Tanpa"
        $opsiTanpa = ['tanpa_bawang' => 'No Bawang', 'tanpa_seledri' => 'No Seledri', 'tanpa_timun' => 'No Timun'];
        foreach($opsiTanpa as $key => $label) {
            if($request->has($key)) { $notes[] = $label; }
        }

        // --- C. LOGIKA SNACK ---
        if($request->filled('varian_rasa')) {
            $notes[] = "Rasa: " . $request->input('varian_rasa');
        }
        if($request->has('extra_saos')) {
            $notes[] = "+Saos Sambal";
        }
        if($request->has('extra_mayo')) {
            $biaya_tambahan += 2000;
            $notes[] = "+Mayonaise";
        }

        // --- FINISHING ---
        $catatan_string = implode(', ', $notes);
        // Harga dasar produk + biaya tambahan (toping/extra)
        $harga_final = $produk->harga + $biaya_tambahan;

        // Masukkan Session Cart
        // CATATAN: Logika ini akan menimpa catatan jika produk ID sama ditambahkan lagi.
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
            // Update harga jika ada perubahan add-ons (mengambil harga terakhir yang diinput user)
            $cart[$id]['price'] = $harga_final; 
            
            if(!empty($catatan_string)) {
                $cart[$id]['note'] = $catatan_string; 
            }
        } else {
            $cart[$id] = [
                "name" => $produk->nama_produk,
                "quantity" => $qty,
                "price" => $harga_final, 
                "image" => $produk->gambar,
                "note" => $catatan_string
            ];
        }

        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Produk masuk keranjang! Total item ini: Rp ' . number_format($harga_final * $qty, 0, ',', '.'));
    }

    // 3. Lihat Keranjang
    public function cart()
    {
        return view('pembeli.cart');
    }

    // 4. Hapus Item
    public function removeCart(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Produk dihapus dari keranjang');
        }
    }

    // 5. Update Jumlah Keranjang
    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            
            // Cek apakah item ada di cart
            if(!isset($cart[$request->id])) {
                 return response()->json(['status' => 'error', 'message' => 'Item tidak ditemukan']);
            }

            $produk = Produk::find($request->id);
            
            // Cek stok realtime
            if(!$produk || $request->quantity > $produk->stock){
                $sisa = $produk ? $produk->stock : 0;
                session()->flash('error', 'Maaf, stok tidak mencukupi! Sisa: ' . $sisa);
                return response()->json(['status' => 'error']);
            }

            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Keranjang berhasil diperbarui!');
        }
    }

    // --- SET LAYANAN (Dine In / Takeaway / Delivery) ---
    public function setLayanan($tipe)
    {
        if(in_array($tipe, ['dine_in', 'takeaway', 'delivery'])) {
            session()->put('jenis_pesanan', $tipe);
            return redirect()->back()->with('success', 'Mode pesanan diubah ke: ' . ucfirst(str_replace('_', ' ', $tipe)));
        }
        return redirect()->back();
    }

    // 6. Proses Checkout
    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk memesan.');
        }

        $fullCart = session()->get('cart');
        if(!$fullCart) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // Logika Checkout Parsial (Item Checklist)
        $selectedIdsString = $request->input('selected_items');
        if(empty($selectedIdsString)) {
            // Jika tidak ada checkbox, anggap checkout semua
            $checkoutItems = $fullCart;
            $selectedIds = array_keys($fullCart);
        } else {
            // Checkout item tertentu saja
            $selectedIds = explode(',', $selectedIdsString);
            $checkoutItems = [];
            foreach($selectedIds as $id) {
                if(isset($fullCart[$id])) {
                    $checkoutItems[$id] = $fullCart[$id];
                }
            }
        }

        if(empty($checkoutItems)) {
            return redirect()->back()->with('error', 'Tidak ada item yang dipilih untuk checkout.');
        }

        // Validasi Stok Sebelum Transaksi
        foreach($checkoutItems as $id => $details){
            $produkCek = Produk::find($id);
            // Cek stok & status
            if(!$produkCek || $produkCek->stock < $details['quantity'] || $produkCek->status == 'non-aktif') {
                return redirect()->back()->with('error', 'Maaf, produk "' . $details['name'] . '" stok habis atau tidak tersedia.');
            }
        }

        // Validasi Input Form
        $jenis_pesanan = session()->get('jenis_pesanan', 'takeaway'); // Default Takeaway jika null
        
        $rules = [
            'no_hp' => 'required|numeric',
            'metode_pembayaran' => 'required|in:tunai,qris',
        ];
        
        if ($jenis_pesanan == 'delivery') {
            $rules['alamat_pengiriman'] = 'required|string|max:255';
            $rules['detail_rumah'] = 'nullable|string|max:255';
        }
        
        $request->validate($rules);

        // Hitung Total Harga
        $total = 0;
        foreach($checkoutItems as $id => $details){
            $total += $details['price'] * $details['quantity'];
        }

        try {
            DB::beginTransaction();

            // 1. Buat Header Transaksi
            $id_transaksi = DB::table('transaksi')->insertGetId([
                'user_id' => Auth::id(),
                'nama_pembeli' => Auth::user()->name,
                'no_hp' => $request->no_hp,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jenis_pesanan' => $jenis_pesanan,
                'alamat_pengiriman' => ($jenis_pesanan == 'delivery') ? $request->alamat_pengiriman : null,
                'detail_rumah' => ($jenis_pesanan == 'delivery') ? $request->detail_rumah : null,
                'kode_transaksi' => 'TRX-' . time() . rand(100,999), // Tambah random biar unik
                'total_harga' => $total,
                'status' => 'pending', 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Buat Detail Transaksi & Kurangi Stok
            foreach($checkoutItems as $id_produk => $details) {
                DB::table('detail_transaksi')->insert([
                    'transaksi_id' => $id_transaksi,
                    'produk_id' => $id_produk,
                    'jumlah' => $details['jumlah'] ?? $details['quantity'], // jaga-jaga naming key
                    'subtotal' => $details['price'] * $details['quantity'],
                    'catatan' => $details['note'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Decrement Stok
                $produk = Produk::find($id_produk);
                if($produk) {
                    $produk->decrement('stock', $details['quantity']);
                }
            }

            // 3. Hapus item yang sudah di-checkout dari Session Cart
            foreach($selectedIds as $id) {
                if(isset($fullCart[$id])) {
                    unset($fullCart[$id]);
                }
            }
            session()->put('cart', $fullCart);
            session()->forget('jenis_pesanan'); 

            DB::commit(); // Simpan permanen

            return redirect()->route('pembeli.index')->with('success', 'Pesanan Berhasil! Silakan selesaikan pembayaran.');

        } catch (Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    // 7. Riwayat Pesanan
    public function riwayat()
    {
        if (!Auth::check()) { return redirect()->route('login'); }

        // Pastikan Model 'Transaksi' memiliki method 'review()' (hasOne/hasMany)
        $riwayat = Transaksi::with('review') 
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pembeli.riwayat', compact('riwayat'));
    }
}