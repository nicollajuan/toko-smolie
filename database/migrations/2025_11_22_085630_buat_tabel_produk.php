<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    // Cek dulu, jika tabel 'produk' BELUM ada, baru buat tabelnya
    if (!Schema::hasTable('produk')) {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('harga');
            $table->string('strok'); // Typo di file lama Anda (strok -> stok), biarkan saja dulu agar tidak error
            $table->string('supplier');
            $table->timestamps();
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};