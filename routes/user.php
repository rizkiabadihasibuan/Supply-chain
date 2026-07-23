<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| USER ROUTES – Authentication Flow
| routes/user.php
|
| All User panel routes with:
| - Prefix:     /dashboard
| - Middleware:  auth, user
| - Controller:  App\Http\Controllers\User\UserController
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
});

Route::prefix('dashboard')
    ->name('user.')
    ->middleware(['auth', 'user'])
    ->group(function () {

        /*
         |--------------------------------------------------------------
         | Countries Module
         | URL: /dashboard/countries
         |--------------------------------------------------------------
         */
        Route::get('/countries', [UserController::class, 'countries'])->name('countries');

        /*
         |--------------------------------------------------------------
         | Weather Module
         | URL: /dashboard/weather
         |--------------------------------------------------------------
         */
        Route::get('/weather', [UserController::class, 'weather'])->name('weather');

        /*
         |--------------------------------------------------------------
         | Currency Exchange Module
         | URL: /dashboard/currency
         |--------------------------------------------------------------
         */
        Route::get('/currency', [UserController::class, 'currency'])->name('currency');

        /*
         |--------------------------------------------------------------
         | News Module
         | URL: /dashboard/news
         |--------------------------------------------------------------
         */
        Route::get('/news', [UserController::class, 'news'])->name('news');

        /*
         |--------------------------------------------------------------
         | Risk Analysis Module
         | URL: /dashboard/risk
         |--------------------------------------------------------------
         */
        Route::get('/risk', [UserController::class, 'risk'])->name('risk');

        /*
         |--------------------------------------------------------------
         | Country Comparison Module
         | URL: /dashboard/comparison
         |--------------------------------------------------------------
         */
        Route::get('/comparison', [UserController::class, 'comparison'])->name('comparison');

        /*
         |--------------------------------------------------------------
         | Favorite / Watchlist Module
         | URL: /dashboard/favorite
         |--------------------------------------------------------------
         */
        Route::get('/favorite', [UserController::class, 'favorite'])->name('favorite');

        /*
         |--------------------------------------------------------------
         | Visualization Module
         | URL: /dashboard/visualization
         |--------------------------------------------------------------
         */
        Route::get('/visualization', [UserController::class, 'visualization'])->name('visualization');

        /*
         |--------------------------------------------------------------
         | Profile Module
         | URL: /dashboard/profile
         |--------------------------------------------------------------
         */
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');

        /*
         |--------------------------------------------------------------
         | Ports Module
         | URL: /dashboard/ports
         |--------------------------------------------------------------
         */
        Route::get('/ports', [UserController::class, 'ports'])->name('ports');

        /*
         |--------------------------------------------------------------
         | Shipping Simulation Module
         | URL: /dashboard/simulation
         |--------------------------------------------------------------
         */
        Route::get('/simulation', [UserController::class, 'simulation'])->name('simulation');

    });
