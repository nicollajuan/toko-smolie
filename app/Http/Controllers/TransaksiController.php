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
        $dataTransaksi = Transaksi::orderBy('created_at', 'desc')->get();
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

    public function kasirMenu()
    {
        if (!Auth::check() || !in_array(Auth::user()->usertype, ['admin', 'kasir'])) {
            abort(403, 'Akses ditolak. Hanya admin atau kasir yang dapat mengakses halaman ini.');
        }

        $produk = Produk::where('status', 'aktif')
                        ->where('stock', '>', 0)
                        ->orderBy('nama_produk', 'asc')
                        ->get();
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
    public function selesai($id)
    {
        // Cari transaksi berdasarkan ID
        $transaksi = Transaksi::findOrFail($id);
        
        // Ubah kolom status jadi 'selesai'
        $transaksi->update([
            'status' => 'selesai'
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diubah menjadi SELESAI!');
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
    public function cetakInvoice($id)
    {
        $transaction = Transaksi::findOrFail($id);

        // KEAMANAN PENTING: Pastikan user tidak mengintip invoice orang lain dengan mengganti ID di URL
        if ($transaction->customer_email != Auth::user()->email) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        // Ambil detail barang juga jika ingin ditampilkan di invoice pembeli
        $details = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->where('detail_transaksi.transaksi_id', $id)
            ->select('detail_transaksi.*', 'produk.nama_produk')
            ->get();

        // Load View PDF Invoice
        $pdf = Pdf::loadView('pembeli.invoice_pdf', compact('transaction', 'details'));
        
        // Download otomatis (Kertas defaultnya adalah A4 portrait)
        return $pdf->download('Invoice-SmolieGift-'.$transaction->id.'.pdf');
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
}