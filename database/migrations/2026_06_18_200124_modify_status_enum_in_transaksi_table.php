<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE transaksi
            MODIFY COLUMN status ENUM(
                'pending',
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan'
            ) DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE transaksi
            MODIFY COLUMN status ENUM(
                'pending',
                'diproses',
                'dikirim',
                'selesai'
            ) DEFAULT 'pending'
        ");
    }
};