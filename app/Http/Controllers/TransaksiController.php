<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;       // Untuk operasi Join tabel
use Barryvdh\DomPDF\Facade\Pdf;          // Untuk cetak PDF
use Illuminate\Support\Facades\Auth;     // Untuk mengambil data user yang login

class TransaksiController extends Controller
{
    // =========================================================================
    // BAGIAN 1: FITUR ADMIN / KASIR
    // =========================================================================

    // Menampilkan semua transaksi masuk ke Admin
    public function index()
    {
        // PERBAIKAN: Tambahkan with('details') untuk meringankan load database Admin
        $dataTransaksi = Transaksi::with('details')->orderBy('created_at', 'desc')->get();
        
        $produk = Produk::where('status', 'aktif')
                        ->where('stock', '>', 0)
                        ->orderBy('nama_produk', 'asc')
                        ->get();

        return view('transaksi.index', compact('dataTransaksi', 'produk'));
    }

    public function createKasir()
    {
        if (!Auth::check() || !in_array(Auth::user()->usertype, ['admin', 'kasir'])) {
            abort(403, 'Akses ditolak. Hanya admin atau kasir yang dapat mengakses halaman ini.');
        }

        $produk = Produk::where('status', 'aktif')
                        ->where('stock', '>', 0)
                        ->orderBy('nama_produk', 'asc')
                        ->get();

        return view('transaksi.manual', compact('produk'));
    }

    public function kasirMenu(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->usertype, ['admin', 'kasir'])) {
            abort(403, 'Akses ditolak. Hanya admin atau kasir yang dapat mengakses halaman ini.');
        }

        $query = Produk::where('status', 'aktif')
                    ->where('stock', '>', 0);

        // Filter pencarian
        if ($request->filled('cari')) {
            $query->where('nama_produk', 'LIKE', '%' . $request->cari . '%');
        }

        $produk = $query->orderBy('nama_produk', 'asc')->get();
        $kategori = Kategori::all();

        return view('transaksi.kasir_menu', compact('produk', 'kategori'));
    }

    public function storeKasir(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->usertype, ['admin', 'kasir'])) {
            abort(403, 'Akses ditolak. Hanya admin atau kasir yang dapat membuat transaksi tunai manual.');
        }

        $rules = [
            'nama_pembeli' => 'required|string|max:255',
            'no_hp' => 'nullable|numeric',
            'produk_id' => 'nullable|exists:produk,id',
            'nama_produk' => 'required_without:produk_id|string|max:255',
            'harga_satuan' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:tunai',
        ];

        $request->validate($rules);

        $produkId = $request->input('produk_id');
        $jumlah = $request->input('jumlah');
        $hargaSatuan = $request->input('harga_satuan');
        $subtotal = $hargaSatuan * $jumlah;

        DB::beginTransaction();

        try {
            if ($produkId) {
                $produk = Produk::findOrFail($produkId);
                if ($produk->stock < $jumlah) {
                    return redirect()->back()->with('error', 'Stok tidak mencukupi untuk produk terpilih.');
                }
            } else {
                $produk = new Produk();
                $produk->nama_produk = $request->input('nama_produk');
                $produk->harga = $hargaSatuan;
                $produk->stock = 0;
                $produk->kategori_id = 'Manual Kasir';
                $produk->supplier = 'Kasir Manual';
                $produk->status = 'aktif';
                $produk->save();
            }

            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'nama_pembeli' => $request->input('nama_pembeli'),
                'no_hp' => $request->input('no_hp'),
                'metode_pembayaran' => 'tunai',
                'jenis_pesanan' => 'offline',
                'kode_transaksi' => 'TRX-' . time() . rand(100, 999),
                'total_harga' => $subtotal,
                'status' => 'selesai',
                'status_pembayaran' => 'berhasil',
                'catatan' => $request->input('catatan'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('detail_transaksi')->insert([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $produk->id,
                'jumlah' => $jumlah,
                'subtotal' => $subtotal,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($produkId) {
                $produk->decrement('stock', $jumlah);
            }

            DB::commit();

            return redirect()->route('transaksi.index')->with('success', 'Transaksi tunai kasir berhasil disimpan.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $exception->getMessage());
        }
    }

    // Mengubah status pesanan menjadi Selesai (Oleh Admin)
    // Mengubah status pesanan menjadi Selesai dan Update Level Member
    public function selesai($id)
    {   
     // Cari transaksi
     $transaksi = Transaksi::findOrFail($id);

     // Ubah kolom status jadi 'selesai'
     $transaksi->update(['status' => 'selesai']);

     // LOGIKA NAIK LEVEL OTOMATIS
     if ($transaksi->user_id) {
         $user = \App\Models\User::find($transaksi->user_id);

         if ($user && $user->usertype == 'user') {
             // Tambahkan nominal transaksi ke total pembelanjaan
             $user->total_pembelanjaan += $transaksi->total_harga;

             // Tentukan level baru berdasarkan total belanja
             if ($user->total_pembelanjaan >= 1000000) {
                 $user->level_member = 'Platinum';
             } elseif ($user->total_pembelanjaan >= 500000) {
                 $user->level_member = 'Gold';
             } elseif ($user->total_pembelanjaan >= 200000) {
                 $user->level_member = 'Silver';
             } else {
                 $user->level_member = 'Bronze';
             }

             $user->save();
         }
     }

     return redirect()->back()->with('success', 'Status pesanan SELESAI. Total belanja pelanggan berhasil diakumulasikan!');
 }

    // Cetak Struk Termal (Oleh Admin/Kasir di Toko)
    public function cetakStruk($id)
    {
        // A. Ambil Data Transaksi Utama
        $transaksi = Transaksi::findOrFail($id);

        // B. Ambil Detail Barang yang dibeli (Join dengan tabel produk)
        $details = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->where('detail_transaksi.transaksi_id', $id)
            ->select('detail_transaksi.*', 'produk.nama_produk')
            ->get();

        // C. Load View PDF Khusus Struk
        $pdf = Pdf::loadView('transaksi.struk', compact('transaksi', 'details'));
        
        // Atur ukuran kertas seperti struk kasir (Custom: 72mm x 600mm)
        $pdf->setPaper([0, 0, 204, 600], 'portrait');

        // D. Tampilkan PDF (Stream = Buka di tab baru, tidak langsung download)
        // Gunakan $transaksi->id jika kode_transaksi belum ada di database kamu
        return $pdf->stream('Struk-' . $transaksi->id . '.pdf'); 
    }


    // =========================================================================
    // BAGIAN 2: FITUR PEMBELI (CUSTOMER)
    // =========================================================================

    // Menampilkan halaman Riwayat Pesanan khusus Pembeli
    // BAGIAN 2: FITUR PEMBELI (CUSTOMER)
    public function riwayat()
    {
        $transactions = Transaksi::where('customer_email', Auth::user()->email)
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        // UBAH BARIS INI: dari 'pembeli.history' menjadi 'pembeli.riwayat'
        return view('pembeli.riwayat', compact('transactions')); 
    }

    // Cetak Invoice Standar A4 (Oleh Pembeli dari Dashboard)
    // Cetak Struk Thermal (Dilihat oleh Pembeli dari Dashboard)
    public function cetakInvoice($id)
    {
        // 1. Cari data transaksi (Gunakan variabel $transaksi agar cocok dengan view struk kasir)
        $transaksi = Transaksi::findOrFail($id);

        // 2. KEAMANAN: Pastikan user tidak mengintip struk orang lain
        if ($transaksi->user_id != Auth::id()) {
            abort(403, 'Akses ditolak! Anda tidak memiliki akses ke struk ini.');
        }

        // 3. Ambil detail barang yang dibeli
        $details = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->where('detail_transaksi.transaksi_id', $id)
            ->select('detail_transaksi.*', 'produk.nama_produk')
            ->get();

        // 4. Load View PDF menggunakan template STRUK KASIR (bukan pembeli.invoice_pdf)
        $pdf = Pdf::loadView('transaksi.struk', compact('transaksi', 'details'));
        
        // 5. Atur ukuran kertas seperti struk kasir (Custom: 72mm x 600mm)
        $pdf->setPaper([0, 0, 204, 600], 'portrait');
        
        // 6. Tampilkan PDF (Stream = Buka di tab baru)
        return $pdf->stream('Struk-SmolieGift-'.$transaksi->kode_transaksi.'.pdf');
    }

    // Mengupdate status menjadi dikirim beserta estimasi tibanya
    public function updatePengiriman(Request $request, $id)
    {
        $request->validate([
            'estimasi_tiba' => 'required|date'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        
        $transaksi->update([
            'status' => 'dikirim',
            'estimasi_tiba' => $request->estimasi_tiba
        ]);

        return redirect()->back()->with('success', 'Status pesanan diubah menjadi DIKIRIM ke ekspedisi!');
    }

    public function manual()
    {
        // Panggil data produk yang statusnya aktif dan stoknya lebih dari 0
        $produk = \App\Models\Produk::where('status', 'aktif')->where('stock', '>', 0)->get();
        
        // Kembalikan ke tampilan halaman manual.blade.php
        // (Catatan: Jika file manual.blade.php ada di dalam folder 'transaksi', biarkan kodenya seperti ini. 
        // Tapi jika ada di luar, cukup tulis view('manual'))
        return view('transaksi.manual', compact('produk'));
    }

    // =========================================================================
    // FITUR CHECKOUT DARI KERANJANG (CART)
    // =========================================================================
    
    public function checkout(Request $request)
    {
        // 1. Validasi Data Input Kasir/Pembeli
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'nullable|numeric',
            'metode_pembayaran' => 'required|in:tunai,qris',
            'jenis_pesanan' => 'required|in:takeaway,delivery,dine_in', 
        ]);

        // 2. Ambil data keranjang dari session (asumsi session bernama 'cart')
        $cart = session()->get('cart');

        if (!$cart || count($cart) == 0) {
            return redirect()->back()->with('error', 'Keranjang kosong. Tidak dapat melakukan checkout.');
        }

        // 3. HITUNG TOTAL HARGA MURNI DULU
        $totalHarga = 0;
        foreach ($cart as $item) {
            $totalHarga += $item['price'] * $item['quantity'];
        }

     if (Auth::check() && Auth::user()->usertype == 'user') {
         $diskon = 0;

         $diskonSetting = \App\Models\DiskonLevel::where('level_member', Auth::user()->level_member)->first();
         if ($diskonSetting) {
             $diskon = $diskonSetting->getDiskonAktif() / 100;
         } else {
             // Fallback jika tabel belum ada
             switch (Auth::user()->level_member) {
                 case 'Platinum': $diskon = 0.15; break;
                 case 'Gold':     $diskon = 0.10; break;
                 case 'Silver':   $diskon = 0.05; break;
             }
         }

         $totalHarga = $totalHarga - ($totalHarga * $diskon); 
     }

        DB::beginTransaction();

        try {
            // 5. Simpan ke tabel Transaksi
            $transaksi = Transaksi::create([
                'user_id' => Auth::id() ?? null, 
                'nama_pembeli' => $request->nama_pelanggan,
                'no_hp' => $request->no_hp,
                'kode_transaksi' => 'TRX-' . time() . rand(100, 999),
                'total_harga' => $totalHarga, // Total harga yang dimasukkan ke DB sudah didiskon
                'metode_pembayaran' => $request->metode_pembayaran,
                'jenis_pesanan' => $request->jenis_pesanan ?? 'takeaway',
                'status' => 'pending', 
                'status_pembayaran' => 'pending', 
            ]);

            // 6. Simpan detail produk ke tabel detail_transaksi & Kurangi Stok
            foreach ($cart as $id => $details) {
                DB::table('detail_transaksi')->insert([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $id,
                    'jumlah' => $details['quantity'],
                    'subtotal' => $details['price'] * $details['quantity'], // Subtotal per barang tetap harga normal
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Opsional: Kurangi stok produk
                Produk::where('id', $id)->decrement('stock', $details['quantity']);
            }

            // 7. Kosongkan keranjang session setelah sukses menyimpan
            session()->forget('cart');

            DB::commit();

            // =========================================================
            // 8. LOGIKA REDIRECT BERDASARKAN METODE PEMBAYARAN (QRIS)
            // =========================================================
            if ($transaksi->metode_pembayaran == 'qris') {
                return redirect()->route('pembayaran.show', $transaksi->id)
                                 ->with('success', 'Pesanan berhasil dibuat! Silakan scan QRIS dan upload bukti pembayaran.');
            } else {
                session()->flash('checkout_summary', [
                    'transaksi_id' => $transaksi->id,
                    'total' => $totalHarga,
                    'metode' => 'tunai',
                    'jenis_pesanan' => $request->jenis_pesanan ?? 'takeaway',
                    'nama_pembeli' => $request->nama_pelanggan,
                    'no_hp' => $request->no_hp,
                    'items' => $cart
                ]);

                return redirect()->back()
                                 ->with('success', 'Pesanan Tunai berhasil dibuat! Silakan cetak struk.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses checkout: ' . $e->getMessage());
        }
    }
}