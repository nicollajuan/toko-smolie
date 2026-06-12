<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'social_id')) {
                $table->string('social_id')->nullable()->after('foto');
            }
            if (!Schema::hasColumn('users', 'social_provider')) {
                $table->string('social_provider')->nullable()->after('social_id');
            }
            // password dibuat nullable karena user login via Google tidak punya password asli
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['social_id', 'social_provider']);
        });
    }
};