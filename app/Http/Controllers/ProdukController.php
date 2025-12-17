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

class ProdukController extends Controller
{
    public function index(){
        // Menampilkan semua produk (baik aktif maupun non-aktif) untuk admin
        $data = Produk::all();
        $kategori = Kategori::all();
        return view('produk.index', ['dataProduk' => $data, 'kategori' => $kategori]);
    }

    public function create(){
        $kategori = Kategori::all();
        return view('produk.create', compact('kategori'));
    }

    public function store(Request $request){
        $message = [
            'required' => ':attribute tidak boleh kosong',
            'unique'   => ':attribute sudah digunakan, pakai kode lain', 
            'numeric'  => ':attribute harus berupa angka',
            'image'    => ':attribute harus berupa gambar',
        ];

        // 1. VALIDASI
        $request->validate([
            // ID Wajib Unique agar tidak bentrok
            'id'          => 'required|numeric|unique:produk,id', 
            'nama_produk' => 'required|unique:produk,nama_produk',
            'kategori_id' => 'required',
            'harga'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $message);

        $data = new Produk();
        
        // 2. INPUT ID MANUAL
        $data->id = $request->id; 
        
        $data->nama_produk = $request->nama_produk;
        $data->kategori_id = $request->kategori_id; 
        $data->harga = $request->harga;
        $data->stock = $request->stock;
        $data->status = 'aktif'; 

        // Upload Gambar
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $nama_file = time()."_".$file->getClientOriginalName();
            $tujuan_upload = 'img/produk';
            $file->move($tujuan_upload, $nama_file);
            $data->gambar = $nama_file;
        }

        $data->save();
        return redirect('/tampil-produk')->with('success','Data berhasil disimpan');
    }

    // --- BAGIAN YANG DIPERBAIKI (UPDATE) ---
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        
        // VALIDASI UPDATE
        // Note: unique:produk,nama_produk,'.$id artinya "Nama boleh sama jika itu milik ID ini sendiri"
        $request->validate([
            'nama_produk' => 'required', 
            'kategori_id' => 'required',
            'harga'       => 'required|numeric',
            'stock'       => 'required|numeric',
        ]);

        $produk->nama_produk = $request->nama_produk;
        $produk->kategori_id = $request->kategori_id;
        $produk->harga = $request->harga;
        $produk->stock = $request->stock;

        // 4. LOGIKA UPDATE STATUS (Agar bisa di-Non-Aktifkan Manual)
        if($request->has('status')){
            $produk->status = $request->status;
        }

        // LOGIKA UPDATE GAMBAR
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if(File::exists(public_path('img/produk/'.$produk->gambar)) && $produk->gambar){
                File::delete(public_path('img/produk/'.$produk->gambar));
            }

            // Upload gambar baru
            $file = $request->file('gambar');
            $nama_file = time()."_".$file->getClientOriginalName();
            $tujuan_upload = 'img/produk';
            $file->move($tujuan_upload, $nama_file);
            
            $produk->gambar = $nama_file;
        }

        $produk->save();

        // [PENTING] INI YANG SEBELUMNYA HILANG
        return redirect('/tampil-produk')->with('success', 'Data berhasil diperbarui!');
    }

    // --- REVISI BAGIAN DESTROY (AUTO ARSIP) ---
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        
        try {
            // 1. Simpan nama gambar dulu
            $gambarLama = $produk->gambar; 
            
            // 2. Coba Hapus Permanen
            $produk->delete();

            // 3. Jika berhasil delete di DB, baru hapus file fisik
            if(File::exists(public_path('img/produk/'.$gambarLama)) && $gambarLama){
                File::delete(public_path('img/produk/'.$gambarLama));
            }

            return redirect('/tampil-produk')->with('success','Data berhasil dihapus permanen');
            
        } catch (QueryException $e) {
            
            // Error 1451: Produk sudah ada di transaksi (Foreign Key Constraint)
            if ($e->errorInfo[1] == 1451) {
                
                // --- AUTO ARSIP ---
                // Ubah status jadi 'non-aktif' alih-alih error
                $produk->status = 'non-aktif';
                $produk->save();

                return redirect('/tampil-produk')
                    ->with('success', 'Produk ini pernah terjual dan tidak bisa dihapus permanen. Status otomatis diubah menjadi "Non-Aktif" (Diarsipkan) agar data transaksi aman.');
            }
            
            // Error lain tetap ditampilkan
            return redirect('/tampil-produk')->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
        }
    }

    public function excel(){
        return Excel::download(new ProdukExport, 'produk.xlsx');
    }

    public function pdf()
    {
        $data = Produk::all();
        $pdf = PDF::loadView('produk.pdfproduk', ['dataProduk' => $data]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('laporan-produk.pdf');
    }

    public function chart(){
        $dataLabel = Produk::orderBy('nama_produk', 'asc')->pluck('nama_produk')->toArray();
        $dataStock = Produk::orderBy('nama_produk', 'asc')->pluck('stock')->toArray();
        return view('produk.chart', compact('dataLabel', 'dataStock'));
    }
}