<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    // Relasi ke User (Yang menulis review)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // --- TAMBAHKAN INI (Relasi ke Transaksi) ---
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}