<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    
    protected $table = 'transaksi'; // Pastikan nama tabel benar
    protected $guarded = [];

    // RELASI KE REVIEW (PENTING!)
    public function review()
    {
        return $this->hasOne(Review::class, 'transaksi_id');
    }
    
    // Relasi lain (jika ada)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}