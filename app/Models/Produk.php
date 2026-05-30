<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    
    protected $table = 'produk';
    protected $primaryKey = 'id';
    
    // ✅ KOREKSI: Masukkan 'kategori_id'. 
    // Saya juga menambahkan 'deskripsi' dan 'status' (karena di ProdukController kamu mengirimkannya juga) 
    // Catatan: 'id' tidak perlu dimasukkan ke fillable karena dia otomatis bertambah (Auto Increment).
    protected $fillable = [
        'kode_produk',
        'nama_produk', 
        'kategori_id', 
        'harga', 
        'stock', 
        'gambar', 
        'deskripsi', 
        'status'
    ];

    public function kategori(){
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}