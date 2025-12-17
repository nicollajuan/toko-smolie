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
        Schema::table('produk', function (Blueprint $table) {
            // Cek dulu apakah kolom belum ada
            if (!Schema::hasColumn('produk', 'kategori_id')) {
                $table->string('kategori_id')->after('nama_produk');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Hapus kolom jika ada saat rollback
            if (Schema::hasColumn('produk', 'kategori_id')) {
                $table->dropColumn('kategori_id');
            }
        });
    }
};
