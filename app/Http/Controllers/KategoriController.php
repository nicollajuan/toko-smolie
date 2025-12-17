<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index(){
        $data = Kategori::all();
        return view('kategori.index', ['dataKategori' => $data]);
    }

    public function create(){
        return view('kategori.create');
    }

    public function store(Request $requests){
        
        $data = new Kategori();
        $data->id = $requests->id; 
        $data->nama_kategori = $requests->nama_kategori; 
        $data->save();
        return redirect('/tampil-kategori');
    }
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->update();

        return redirect('/tampil-kategori');
    }
    
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect('/tampil-kategori');
    }

}