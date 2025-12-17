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
            // Tambah kolom username jika belum ada
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('name');
            }
            // Tambah kolom foto profil jika belum ada
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo', 2048)->nullable()->after('email');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'profile_photo']);
        });
    }
};
