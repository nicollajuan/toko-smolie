<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Exports\ProdukExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    // =========================================================
    // FUNGSI PENJAGA PINTU (MENGGANTIKAN MIDDLEWARE)
    // =========================================================
    private function cekAdmin()
    {
        if (!Auth::check() || Auth::user()->usertype !== 'admin') {
            abort(403, 'AKSES DITOLAK! Hanya Admin yang boleh mengelola halaman ini.');
        }
    }

    // =========================================================
    // FUNGSI UTAMA
    // =========================================================

    public function index(){
        $this->cekAdmin();

        $data = Produk::all();
        $kategori = Kategori::all();
        return view('produk.index', ['dataProduk' => $data, 'kategori' => $kategori]);
    }

    public function create(){
        $this->cekAdmin();

        $kategori = Kategori::all();
        return view('produk.create', compact('kategori'));
    }

    public function store(Request $request){
        $this->cekAdmin();

        $message = [
            'required' => ':attribute tidak boleh kosong',
            'unique'   => ':attribute sudah digunakan, pakai kode lain',
            'numeric'  => ':attribute harus berupa angka',
            'image'    => ':attribute harus berupa gambar',
        ];

        $request->validate([
            'nama_produk' => 'required|unique:produk,nama_produk',
            'kategori_id' => 'required',
            'harga'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $message);

        $data = new Produk();

        // Auto-generate kode produk unik
        $kode_baru = 'SML-' . strtoupper(Str::random(5));
        while (Produk::where('kode_produk', $kode_baru)->exists()) {
            $kode_baru = 'SML-' . strtoupper(Str::random(5));
        }
        $data->kode_produk = $kode_baru;

        $data->nama_produk = $request->nama_produk;
        $data->kategori_id = $request->kategori_id;
        $data->harga       = $request->harga;
        $data->stock       = $request->stock;
        $data->status      = 'aktif';
        $data->deskripsi   = $request->has('deskripsi') ? $request->deskripsi : '-';

        if ($request->hasFile('gambar')) {
            $file      = $request->file('gambar');
            $nama_file = time() . "_" . $file->getClientOriginalName();

            // Pastikan folder ada, buat jika belum
            $tujuan_upload = public_path('img/produk');
            if (!File::exists($tujuan_upload)) {
                File::makeDirectory($tujuan_upload, 0755, true);
            }

            $file->move($tujuan_upload, $nama_file);
            $data->gambar = $nama_file;
        }

        $data->save();
        return redirect('/tampil-produk')->with('success', 'Data berhasil disimpan dengan Kode: ' . $kode_baru);
    }

    public function update(Request $request, $id)
    {
        $this->cekAdmin();

        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required',
            'kategori_id' => 'required',
            'harga'       => 'required|numeric',
            'stock'       => 'required|numeric',
        ]);

        $produk->nama_produk = $request->nama_produk;
        $produk->kategori_id = $request->kategori_id;
        $produk->harga       = $request->harga;
        $produk->stock       = $request->stock;

        if ($request->has('deskripsi')) {
            $produk->deskripsi = $request->deskripsi;
        }

        if ($request->has('status')) {
            $produk->status = $request->status;
        }

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            $gambarLama = public_path('img/produk/' . $produk->gambar);
            if ($produk->gambar && File::exists($gambarLama)) {
                File::delete($gambarLama);
            }

            $file      = $request->file('gambar');
            $nama_file = time() . "_" . $file->getClientOriginalName();

            $tujuan_upload = public_path('img/produk');
            if (!File::exists($tujuan_upload)) {
                File::makeDirectory($tujuan_upload, 0755, true);
            }

            $file->move($tujuan_upload, $nama_file);
            $produk->gambar = $nama_file;
        }

        $produk->save();
        return redirect('/tampil-produk')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->cekAdmin();
        $produk = Produk::findOrFail($id);

        try {
            // Lepas relasi dulu
            \DB::table('detail_transaksi')
                ->where('produk_id', $id)
                ->update(['produk_id' => null]);

            $gambarLama = $produk->gambar;
            $produk->delete();

            if ($gambarLama && File::exists(public_path('img/produk/' . $gambarLama))) {
                File::delete(public_path('img/produk/' . $gambarLama));
            }

            return redirect('/tampil-produk')->with('success', 'Data berhasil dihapus permanen');

        } catch (QueryException $e) {
            return redirect('/tampil-produk')->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function excel(){
        $this->cekAdmin();
        return Excel::download(new ProdukExport, 'produk.xlsx');
    }

    public function pdf()
    {
        $this->cekAdmin();
        $data = Produk::all();
        $pdf  = PDF::loadView('produk.pdfproduk', ['dataProduk' => $data]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('laporan-produk.pdf');
    }

    public function chart(){
        $this->cekAdmin();
        $dataLabel = Produk::orderBy('nama_produk', 'asc')->pluck('nama_produk')->toArray();
        $dataStock = Produk::orderBy('nama_produk', 'asc')->pluck('stock')->toArray();
        return view('produk.chart', compact('dataLabel', 'dataStock'));
    }
}