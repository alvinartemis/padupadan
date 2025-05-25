<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Extensions\StylistUserProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Auth::provider('stylist', function ($app, array $config) {
            return new StylistUserProvider($app['hash'], $config['model']);
        });
    }
}
