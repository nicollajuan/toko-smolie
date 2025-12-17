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
        // Jenis pesanan: dine_in, takeaway, delivery
        $table->string('jenis_pesanan')->default('takeaway')->after('status'); 
        // Kolom khusus delivery
        $table->text('alamat_pengiriman')->nullable()->after('jenis_pesanan');
        $table->text('detail_rumah')->nullable()->after('alamat_pengiriman'); // Patokan/Pagar warna apa
    });
}

public function down()
{
    Schema::table('transaksi', function (Blueprint $table) {
        $table->dropColumn(['jenis_pesanan', 'alamat_pengiriman', 'detail_rumah']);
    });
}
};
