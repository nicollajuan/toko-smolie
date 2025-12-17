<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'nama_produk', 'harga', 'stock', 'gambar'];

    public function kategori(){
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}