<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return response()->json([
            'status' => 'success',
            'data'   => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $kategori = Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);
        return response()->json([
            'status' => 'success',
            'data'   => $kategori
        ]);
    }
    public function update(Request $request, $id) {
    $kategori = Kategori::findOrFail($id);
    $kategori->nama_kategori = $request->nama_kategori;
    $kategori->save();
    return response()->json(['status' => 'success']);
    }

    public function destroy($id) {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        return response()->json(['status' => 'success']);
    }
}