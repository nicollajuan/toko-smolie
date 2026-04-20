<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Transaksi;
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
        $produk = Produk::with('kategori')->get(); 
        $kategori = Kategori::all(); 
        
        return view('pembeli.index', compact('produk', 'kategori'));
    }

    // 2. Tambah ke Keranjang (KOREKSI LOGIKA SOUVENIR)
    public function addToCart(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        
        // Security Layer: Cek ketersediaan
        if($produk->status == 'non-aktif' || $produk->stock <= 0) {
            return redirect()->back()->with('error', 'Maaf, produk ini sedang tidak tersedia.');
        }

        $cart = session()->get('cart', []);
        
        // Menangkap input jumlah (dari name="qty" di HTML baru)
        $qty = (int) $request->input('qty', 1);
        if($qty < 1) $qty = 1;

        $notes = [];
        $biaya_tambahan = 0;

        // --- A. LOGIKA WARNA / MOTIF ---
        if($request->filled('warna')) { 
            $notes[] = "Warna: " . $request->input('warna'); 
        }

        // --- B. LOGIKA KEMASAN ---
        if($request->filled('kemasan')) {
            $kemasan = $request->input('kemasan');
            $notes[] = "Kemasan: " . $kemasan;
            
            // Tambah harga sesuai pilihan kemasan
            if($kemasan == 'Tile') {
                $biaya_tambahan += 1000;
            } elseif($kemasan == 'Box') {
                $biaya_tambahan += 2500;
            }
        }

        // --- C. LOGIKA EKSTRA (Sablon & Kartu) ---
        if($request->has('ekstra')) {
            $ekstras = $request->input('ekstra'); // Ini berupa array dari checkbox
            if(is_array($ekstras)){
                foreach($ekstras as $eks) {
                    $notes[] = "+ " . $eks;
                    
                    if($eks == 'Sablon') {
                        $biaya_tambahan += 500;
                    } elseif($eks == 'Kartu Ucapan') {
                        $biaya_tambahan += 300;
                    }
                }
            }
        }

        // --- D. LOGIKA CATATAN CUSTOM DESAIN ---
        if($request->filled('catatan_desain')) {
            $notes[] = 'Catatan: "' . $request->input('catatan_desain') . '"';
        }

        // --- E. LOGIKA UPLOAD FILE DESAIN ---
        if($request->hasFile('file_desain')) {
            $file = $request->file('file_desain');
            $nama_file = time() . "_desain_" . $file->getClientOriginalName();
            // Simpan di folder public/img/custom_desain
            $file->move(public_path('img/custom_desain'), $nama_file);
            
            // Masukkan nama file ke catatan agar admin bisa mencarinya nanti
            $notes[] = "[File Desain: " . $nama_file . "]";
        }

        // --- FINISHING ---
        $catatan_string = implode(' | ', $notes); // Menggunakan pembatas " | " agar lebih rapi dibaca
        
        // Harga dasar produk + biaya tambahan add-on
        $harga_final = $produk->harga + $biaya_tambahan;

        // Masukkan Session Cart
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
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
        
        return redirect()->back()->with('success', 'Souvenir masuk keranjang! Total item ini: Rp ' . number_format($harga_final * $qty, 0, ',', '.'));
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
            
            if(!isset($cart[$request->id])) {
                 return response()->json(['status' => 'error', 'message' => 'Item tidak ditemukan']);
            }

            $produk = Produk::find($request->id);
            
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

    // --- SET LAYANAN (Bisa diganti jadi Pickup / Delivery) ---
    public function setLayanan($tipe)
    {
        if(in_array($tipe, ['dine_in', 'takeaway', 'delivery'])) {
            session()->put('jenis_pesanan', $tipe);
            return redirect()->back()->with('success', 'Mode pengiriman diubah ke: ' . ucfirst(str_replace('_', ' ', $tipe)));
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

        $selectedIdsString = $request->input('selected_items');
        if(empty($selectedIdsString)) {
            $checkoutItems = $fullCart;
            $selectedIds = array_keys($fullCart);
        } else {
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
            if(!$produkCek || $produkCek->stock < $details['quantity'] || $produkCek->status == 'non-aktif') {
                return redirect()->back()->with('error', 'Maaf, produk "' . $details['name'] . '" stok habis atau tidak tersedia.');
            }
        }

        // Validasi Input Form
        $jenis_pesanan = session()->get('jenis_pesanan', 'takeaway'); 
        
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
                'kode_transaksi' => 'TRX-' . time() . rand(100,999), 
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
                    'jumlah' => $details['jumlah'] ?? $details['quantity'], 
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

            DB::commit(); 

            return redirect()->route('pembayaran.show', $id_transaksi)->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (Exception $e) {
            DB::rollBack(); 
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    // 7. Riwayat Pesanan
    public function riwayat()
    {
        if (!Auth::check()) { return redirect()->route('login'); }

        $riwayat = Transaksi::with('review') 
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pembeli.riwayat', compact('riwayat'));
    }
}