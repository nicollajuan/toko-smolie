<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
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
        return view('transaksi.index', compact('dataTransaksi'));
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
}