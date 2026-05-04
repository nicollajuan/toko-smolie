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
            // Menambahkan kolom deskripsi (tipe 'text' karena deskripsi biasanya panjang)
            // 'nullable()' artinya kolom ini boleh kosong jika user tidak mengisinya
            $table->text('deskripsi')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn('deskripsi');
        });
    }
};