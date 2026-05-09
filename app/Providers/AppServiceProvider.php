<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;

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

        // Share nomor WhatsApp admin ke semua view agar tautan WhatsApp otomatis mengikuti data admin
        $adminWhatsApp = User::where('usertype', 'admin')->first()?->whatsapp;
        view()->share('site_whatsapp', $adminWhatsApp ?: '62895395810940');
    }
}
