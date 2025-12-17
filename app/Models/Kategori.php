<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    
    protected $table = 'kategori';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'nama_kategori'];

    public function produk(){
        return $this->hasMany(Produk::class);
    }
}