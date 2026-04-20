<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    
    protected $table = 'transaksi';
    protected $fillable = [
        'user_id',
        'nama_pembeli',
        'kode_transaksi',
        'total_harga',
        'metode_pembayaran',
        'status',
        'status_pembayaran',
        'bukti_pembayaran',
        'qris_data',
        'catatan',
        'tipe_pengambilan',
        'alamat_pengiriman',
    ];

    // RELASI KE USER
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI KE DETAIL TRANSAKSI
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    // RELASI KE REVIEW (PENTING!)
    public function review()
    {
        return $this->hasOne(Review::class, 'transaksi_id');
    }
}