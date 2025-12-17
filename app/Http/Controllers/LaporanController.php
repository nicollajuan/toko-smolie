<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB; // <--- WAJIB ADA untuk hitung total terjual

class LaporanController extends Controller
{
    public function index()
    {
        // 1. DATA UNTUK TABEL (RIWAYAT PEMBELI)
        // Mengambil data transaksi urut dari yang terbaru
        $laporan = Transaksi::orderBy('created_at', 'desc')->get();

        // 2. DATA UNTUK GRAFIK (PRODUK TERLARIS)
        $produkAll = Produk::all();
        
        $dataLabel = [];
        $dataPenjualan = [];

        foreach($produkAll as $p) {
            // Masukkan nama produk ke label
            $dataLabel[] = $p->nama_produk;
            
            // Hitung total 'jumlah' dari tabel detail_transaksi berdasarkan ID produk
            $totalTerjual = DB::table('detail_transaksi')
                ->where('produk_id', $p->id)
                ->sum('jumlah');
                
            // Masukkan hasil hitungan ke data grafik
            $dataPenjualan[] = (int) $totalTerjual;
        }

        // 3. Kirim SEMUA data ke view
        // Perhatikan nama variabelnya: $dataPenjualan (bukan dataStock lagi)
        return view('laporan.index', compact('laporan', 'dataLabel', 'dataPenjualan'));
    }

    // Fungsi Download Excel
    public function exportExcel()
    {
        return Excel::download(new LaporanExport, 'laporan_penjualan.xlsx');
    }

    // Fungsi Download PDF
    public function exportPdf()
    {
        // Ambil data untuk dicetak di PDF
        $laporan = Transaksi::orderBy('created_at', 'desc')->get();
        
        $pdf = Pdf::loadView('laporan.pdf', compact('laporan'));
        $pdf->setPaper('a4', 'landscape'); 
        
        return $pdf->download('laporan_penjualan.pdf');
    }
}