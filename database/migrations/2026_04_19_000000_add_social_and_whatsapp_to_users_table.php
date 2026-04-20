<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Fields untuk Facebook login
            if (!Schema::hasColumn('users', 'social_id')) {
                $table->string('social_id')->nullable()->unique();
            }
            
            if (!Schema::hasColumn('users', 'social_provider')) {
                $table->string('social_provider')->nullable();
            }
            
            // Field untuk WhatsApp admin
            if (!Schema::hasColumn('users', 'whatsapp')) {
                $table->string('whatsapp')->nullable();
            }
            
            // Field untuk foto profil dari social media
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'social_id')) {
                $table->dropColumn('social_id');
            }
            if (Schema::hasColumn('users', 'social_provider')) {
                $table->dropColumn('social_provider');
            }
            if (Schema::hasColumn('users', 'whatsapp')) {
                $table->dropColumn('whatsapp');
            }
            if (Schema::hasColumn('users', 'profile_photo')) {
                $table->dropColumn('profile_photo');
            }
        });
    }
};
