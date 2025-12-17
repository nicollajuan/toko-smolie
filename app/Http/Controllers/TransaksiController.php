<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
// --- IMPORT LIBRARY DI BAWAH INI SANGAT PENTING ---
use Illuminate\Support\Facades\DB;      // <--- Agar bisa baca DB::table
use Barryvdh\DomPDF\Facade\Pdf;         // <--- Agar bisa cetak PDF

class TransaksiController extends Controller
{
    // 1. Halaman Riwayat Transaksi
    public function index()
    {
        $dataTransaksi = Transaksi::orderBy('created_at', 'desc')->get();
        return view('transaksi.index', compact('dataTransaksi'));
    }

    // 2. Fungsi Mengubah Status Jadi Selesai
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

    // 3. Fungsi Cetak Struk (PDF)
    public function cetakStruk($id)
    {
        // A. Ambil Data Transaksi Utama
        $transaksi = Transaksi::findOrFail($id);

        // B. Ambil Detail Barang yang dibeli (Join dengan tabel produk)
        // Ini yang tadi error karena DB:: belum di-import
        $details = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->where('detail_transaksi.transaksi_id', $id)
            ->select('detail_transaksi.*', 'produk.nama_produk')
            ->get();

        // C. Load View PDF Khusus Struk
        $pdf = Pdf::loadView('transaksi.struk', compact('transaksi', 'details'));
        
        // Atur ukuran kertas seperti struk kasir (Custom: 72mm x 600mm)
        $pdf->setPaper([0, 0, 204, 600], 'portrait');

        // D. Tampilkan PDF (Stream)
        return $pdf->stream('Struk-' . $transaksi->kode_transaksi . '.pdf');
    }
}