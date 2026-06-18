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
        // Mengecek apakah kolom 'kode_produk' belum ada di tabel 'produk'
        if (!Schema::hasColumn('produk', 'kode_produk')) {
            Schema::table('produk', function (Blueprint $table) {
                $table->string('kode_produk')->nullable()->unique()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengecek apakah kolom 'kode_produk' memang ada sebelum dihapus (rollback)
        if (Schema::hasColumn('produk', 'kode_produk')) {
            Schema::table('produk', function (Blueprint $table) {
                $table->dropColumn('kode_produk');
            });
        }
    }
};