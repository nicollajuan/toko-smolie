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

        $request->validate([
            'id'          => 'required|numeric|unique:produk,id', 
            'nama_produk' => 'required|unique:produk,nama_produk',
            'kategori_id' => 'required',
            'harga'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $message);

        $data = new Produk();
        
        $data->id = $request->id; 
        $data->nama_produk = $request->nama_produk;
        $data->kategori_id = $request->kategori_id; 
        $data->harga = $request->harga;
        $data->stock = $request->stock;
        $data->status = 'aktif'; 
        
        // --- KOREKSI: Tambahkan nilai default untuk deskripsi ---
        // Anda bisa mengambilnya dari form jika ada form input 'deskripsi', 
        // tapi jika tidak ada di form, kita isi string kosong '-' agar DB tidak error.
        $data->deskripsi = $request->has('deskripsi') ? $request->deskripsi : '-';

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

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        
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

        // --- KOREKSI UPDATE: Pastikan deskripsi juga ditangkap (opsional) ---
        if($request->has('deskripsi')){
            $produk->deskripsi = $request->deskripsi;
        }

        if($request->has('status')){
            $produk->status = $request->status;
        }

        if ($request->hasFile('gambar')) {
            if(File::exists(public_path('img/produk/'.$produk->gambar)) && $produk->gambar){
                File::delete(public_path('img/produk/'.$produk->gambar));
            }

            $file = $request->file('gambar');
            $nama_file = time()."_".$file->getClientOriginalName();
            $tujuan_upload = 'img/produk';
            $file->move($tujuan_upload, $nama_file);
            
            $produk->gambar = $nama_file;
        }

        $produk->save();

        return redirect('/tampil-produk')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        
        try {
            $gambarLama = $produk->gambar; 
            
            $produk->delete();

            if(File::exists(public_path('img/produk/'.$gambarLama)) && $gambarLama){
                File::delete(public_path('img/produk/'.$gambarLama));
            }

            return redirect('/tampil-produk')->with('success','Data berhasil dihapus permanen');
            
        } catch (QueryException $e) {
            
            if ($e->errorInfo[1] == 1451) {
                
                $produk->status = 'non-aktif';
                $produk->save();

                return redirect('/tampil-produk')
                    ->with('success', 'Produk ini pernah terjual dan tidak bisa dihapus permanen. Status otomatis diubah menjadi "Non-Aktif" (Diarsipkan) agar data transaksi aman.');
            }
            
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