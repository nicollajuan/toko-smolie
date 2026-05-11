<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use App\Helpers\AdminHelper;
use Illuminate\Support\Facades\Schema; // <-- WAJIB DITAMBAHKAN AGAR TIDAK ERROR

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //masukkan key dan value yang akan dishare
        view()->share('nilai', 300000);
        
        // --- GABUNGAN KODE LOKAL & GITHUB YANG SUDAH AMAN ---
        
        // 1. Share nomor WhatsApp admin (Versi Lokalmu)
        // Mengecek dulu apakah tabel 'users' dan kolom 'whatsapp' sudah ada
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'whatsapp')) {
            $adminWhatsApp = User::where('usertype', 'admin')->first()?->whatsapp;
            view()->share('site_whatsapp', $adminWhatsApp ?: '62895395810940');
        } else {
            // Jika belum ada (misal di laptop temanmu yang belum migrate), pakai nomor default
            view()->share('site_whatsapp', '62895395810940');
        }
        
        // 2. Share admin helper (Versi dari GitHub)
        view()->share([
            'adminHelper' => new AdminHelper(),
            'adminWhatsApp' => AdminHelper::getAdminWhatsApp(),
            'adminContactInfo' => AdminHelper::getAdminContactInfo(),
        ]);
    }
}