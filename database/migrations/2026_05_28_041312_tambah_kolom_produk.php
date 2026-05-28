<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Tambah kolom yang belum ada
            $table->string('stock')->nullable()->after('harga');
            $table->string('gambar')->nullable()->after('stock');
            $table->unsignedBigInteger('kategori_id')->nullable()->after('gambar');
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['stock', 'gambar', 'kategori_id']);
        });
    }
};