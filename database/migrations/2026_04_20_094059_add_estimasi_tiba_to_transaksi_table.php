<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Menambahkan kolom estimasi_tiba setelah kolom status
            $table->date('estimasi_tiba')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Menghapus kolom jika sewaktu-waktu kita membatalkan migrasi
            $table->dropColumn('estimasi_tiba');
        });
    }
};
