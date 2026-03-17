<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Cek dan tambah laci satu per satu jika belum ada
            if (!Schema::hasColumn('transaksi', 'nama_pembeli')) {
                $table->string('nama_pembeli')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('transaksi', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('nama_pembeli');
            }
            if (!Schema::hasColumn('transaksi', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('transaksi', 'jenis_pesanan')) {
                $table->string('jenis_pesanan')->nullable()->after('metode_pembayaran');
            }
            if (!Schema::hasColumn('transaksi', 'alamat_pengiriman')) {
                $table->text('alamat_pengiriman')->nullable()->after('jenis_pesanan');
            }
            if (!Schema::hasColumn('transaksi', 'detail_rumah')) {
                $table->string('detail_rumah')->nullable()->after('alamat_pengiriman');
            }
            if (!Schema::hasColumn('transaksi', 'kode_transaksi')) {
                $table->string('kode_transaksi')->nullable()->after('detail_rumah');
            }
            if (!Schema::hasColumn('transaksi', 'total_harga')) {
                $table->integer('total_harga')->nullable()->after('kode_transaksi');
            }
            if (!Schema::hasColumn('transaksi', 'status')) {
                $table->string('status')->default('pending')->after('total_harga');
            }
        });
    }

    public function down(): void
    {
        // Biarkan kosong saja agar data aman saat di-rollback
    }
};