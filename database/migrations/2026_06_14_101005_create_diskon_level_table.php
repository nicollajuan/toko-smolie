<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diskon_level', function (Blueprint $table) {
            $table->id();
            $table->string('level_member'); // Bronze, Silver, Gold, Platinum
            $table->decimal('persentase_diskon', 5, 2)->default(0); // diskon per level (%)
            $table->boolean('diskon_manual_aktif')->default(false); // aktifkan diskon manual?
            $table->decimal('nominal_diskon_manual', 5, 2)->default(0); // override manual (%)
            $table->timestamps();
        });

        // Seed data default
        DB::table('diskon_level')->insert([
            ['level_member' => 'Bronze',   'persentase_diskon' => 0,  'diskon_manual_aktif' => false, 'nominal_diskon_manual' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['level_member' => 'Silver',   'persentase_diskon' => 5,  'diskon_manual_aktif' => false, 'nominal_diskon_manual' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['level_member' => 'Gold',     'persentase_diskon' => 10, 'diskon_manual_aktif' => false, 'nominal_diskon_manual' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['level_member' => 'Platinum', 'persentase_diskon' => 15, 'diskon_manual_aktif' => false, 'nominal_diskon_manual' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('diskon_level');
    }
};
