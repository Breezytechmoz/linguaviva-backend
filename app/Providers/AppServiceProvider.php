<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request; // Necessário para o Proxy

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
        // Força HTTPS em produção (Render)
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        // Confiar no proxy do Render
        if (isset($_SERVER['REMOTE_ADDR'])) {
            Request::setTrustedProxies(
                [$_SERVER['REMOTE_ADDR']],
                Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
            );
        }
    }
}