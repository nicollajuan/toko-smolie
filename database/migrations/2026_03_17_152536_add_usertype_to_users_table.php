<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Gunakan jurus pamungkas pengecekan kolom
            if (!Schema::hasColumn('users', 'usertype')) {
                // Kita beri default 'user' agar aman
                $table->string('usertype')->default('user')->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'usertype')) {
                $table->dropColumn('usertype');
            }
        });
    }
};