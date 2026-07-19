<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\Auth::extend('sanctum', function ($app, $name, array $config) {
            return new \Illuminate\Auth\RequestGuard(function ($request) use ($app) {
                return \Illuminate\Support\Facades\Auth::guard('web')->user();
            }, $app['request'], $app['auth']->createUserProvider($config['provider'] ?? null));
        });
    }
}
