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
    Schema::table('users', function (Blueprint $table) {
        $table->integer('total_pembelanjaan')->default(0)->after('password');
        $table->string('level_member')->default('Bronze')->after('total_pembelanjaan');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['total_pembelanjaan', 'level_member']);
    });
}
};
