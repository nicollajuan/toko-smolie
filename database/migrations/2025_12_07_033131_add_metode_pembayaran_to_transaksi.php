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
        // Default tunai agar data lama aman
        $table->string('metode_pembayaran')->default('tunai')->after('total_harga'); 
    });
}

public function down()
{
    Schema::table('transaksi', function (Blueprint $table) {
        $table->dropColumn('metode_pembayaran');
    });
}};
