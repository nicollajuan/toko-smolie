<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. BUAT TABEL INDUK DULU ('transaksi')
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            // Jika Anda sudah menambahkan user_id sebelumnya, masukkan di sini
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); 
            $table->string('nama_pembeli');
            $table->string('kode_transaksi')->unique();
            $table->decimal('total_harga', 15, 2);
            $table->enum('status', ['pending', 'selesai'])->default('pending');
            $table->timestamps();
        });

        // 2. BARU BUAT TABEL ANAK ('detail_transaksi')
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();
            // Pastikan referensinya ke 'transaksi' (sesuai nama tabel di atas)
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk'); 
            $table->integer('jumlah');
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        // Hapus tabel anak dulu, baru induk
        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
    }
};