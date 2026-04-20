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
        Schema::table('transaksi', function (Blueprint $table) {
            // Field untuk status pembayaran
            $table->enum('status_pembayaran', ['pending', 'berhasil', 'gagal'])->default('pending')->nullable();
            
            // Field untuk bukti pembayaran
            $table->string('bukti_pembayaran')->nullable();
            
            // Field untuk menyimpan data QRIS (opsional, untuk audit)
            $table->text('qris_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['status_pembayaran', 'bukti_pembayaran', 'qris_data']);
        });
    }
};
