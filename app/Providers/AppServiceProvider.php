<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\AdminHelper;

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
        
        // Share admin helper ke semua view
        view()->share([
            'adminHelper' => new AdminHelper(),
            'adminWhatsApp' => AdminHelper::getAdminWhatsApp(),
            'adminContactInfo' => AdminHelper::getAdminContactInfo(),
        ]);
    }
}
