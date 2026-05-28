<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi', 'uang_diterima')) {
                $table->decimal('uang_diterima', 15, 2)->default(0)->after('metode_pembayaran');
            }
            if (!Schema::hasColumn('transaksi', 'kembalian')) {
                $table->decimal('kembalian', 15, 2)->default(0)->after('uang_diterima');
            }
        });
    }

    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi', 'kembalian')) {
                $table->dropColumn('kembalian');
            }
            if (Schema::hasColumn('transaksi', 'uang_diterima')) {
                $table->dropColumn('uang_diterima');
            }
        });
    }
};
