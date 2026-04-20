<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;
    
    protected $table = 'detail_transaksi';
    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'jumlah',
        'subtotal',
        'catatan',
    ];

    // RELASI KE TRANSAKSI
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // RELASI KE PRODUK
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
