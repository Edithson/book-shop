<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
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
        // 1. On récupère les paramètres depuis le cache.
        // S'ils n'y sont pas, on fait la requête et on les met en cache indéfiniment.
        $globalSettings = Cache::rememberForever('global_settings', function () {
            return Setting::first() ?? new Setting();
        });

        // 2. On partage cette variable avec toutes les vues Blade de l'application
        View::share('globalSettings', $globalSettings);
    }
}
