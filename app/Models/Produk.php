<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $fillable = [
    'nama_produk',
    'harga',
    'stock', 
    'gambar',
    'kategori_id',  // ← tambah ini
    'deskripsi',    // ← tambah ini
    'status',       // ← tambah ini
];

    public function kategori(){
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}