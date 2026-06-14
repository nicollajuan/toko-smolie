<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    // GET semua produk (hanya aktif)
    public function index()
    {
        $produk = Produk::where('status', 'aktif')->get();
        return response()->json([
            'status' => 'success',
            'data'   => $produk
        ]);
    }

    // POST tambah produk
    public function store(Request $request)
    {
        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'harga'       => $request->harga,
            'stock'       => $request->stock,
            'gambar'      => $request->gambar ?? '',
            'kategori_id' => $request->kategori_id ?? '',
            'deskripsi'   => $request->deskripsi ?? '',
            'status'      => $request->status ?? 'aktif',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Produk ditambahkan',
            'data'    => $produk
        ]);
    }

    // PUT update produk
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->update([
            'nama_produk' => $request->nama_produk,
            'harga'       => $request->harga,
            'stock'       => $request->stock,
            'gambar'      => $request->gambar ?? $produk->gambar,
            'kategori_id' => $request->kategori_id ?? $produk->kategori_id,
            'deskripsi'   => $request->deskripsi ?? $produk->deskripsi,
            'status'      => $request->status ?? $produk->status,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Produk diupdate',
            'data'    => $produk
        ]);
    }

    // DELETE hapus produk
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        try {
            // Lepas relasi ke detail_transaksi agar tidak kena foreign key constraint
            // produk_id di detail_transaksi di-set NULL — data transaksi tetap aman
            DB::table('detail_transaksi')
                ->where('produk_id', $id)
                ->update(['produk_id' => null]);

            // Hapus gambar fisik jika ada
            $imagePath = public_path('img/produk/' . $produk->gambar);
            if ($produk->gambar && file_exists($imagePath)) {
                unlink($imagePath);
            }

            $produk->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Produk berhasil dihapus'
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ]);
        }
    }

    // POST upload gambar
    public function uploadImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ada file']);
        }

        $file     = $request->file('image');
        $filename = 'produk_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('img/produk'), $filename);

        return response()->json([
            'status'   => 'success',
            'filename' => $filename
        ]);
    }
}