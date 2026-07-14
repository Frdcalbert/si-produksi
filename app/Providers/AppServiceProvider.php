<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // ✅ FORCE HTTPS DI SEMUA ENVIRONMENT
        URL::forceScheme('https');
    }
}