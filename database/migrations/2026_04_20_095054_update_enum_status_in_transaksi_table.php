<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Menambahkan 'dikirim' ke dalam daftar pilihan ENUM
        // Sesuaikan kata-kata di dalam kurung dengan status yang selama ini kamu pakai
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN status ENUM('pending', 'diproses', 'dikirim', 'selesai') DEFAULT 'pending'");
    }

    public function down()
    {
        // Jika migrasi dibatalkan, kembalikan seperti semula tanpa 'dikirim'
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN status ENUM('pending', 'diproses', 'selesai') DEFAULT 'pending'");
    }
};