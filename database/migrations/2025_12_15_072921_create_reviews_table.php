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
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('transaksi_id');
        $table->unsignedBigInteger('user_id');
        $table->integer('rating');
        $table->text('komentar')->nullable();
        $table->timestamps();

        // Opsional: Foreign Key agar aman
        // $table->foreign('transaksi_id')->references('id')->on('transaksi')->onDelete('cascade');
        // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
