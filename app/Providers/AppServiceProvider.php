<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
        {
            if (config('app.env') !== 'local') {
                URL::forceScheme('https');
            }
        }

        // Confiar no proxy do Render
    if (isset($_SERVER['REMOTE_ADDR'])) {
        \Illuminate\Support\Facades\Request::setTrustedProxies(
            [$_SERVER['REMOTE_ADDR']],
            \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
            \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
            \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
            \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO
        );
    }
}
