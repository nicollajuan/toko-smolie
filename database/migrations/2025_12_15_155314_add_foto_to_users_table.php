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
        // Menambahkan kolom foto setelah kolom email, boleh kosong (nullable)
        $table->string('foto')->nullable()->after('email');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('foto');
    });
}
};
