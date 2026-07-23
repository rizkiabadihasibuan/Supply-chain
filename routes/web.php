<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB ROUTES – Authentication Flow
|--------------------------------------------------------------------------
|
| Struktur Routing:
|   ├── /                     → Landing Page (guest)
|   ├── /login, /register     → Auth pages (guest.redirect)
|   ├── /dashboard/*          → User Panel (auth + user middleware)
|   ├── /admin/*              → Admin Panel (auth + admin middleware)
|   └── Shared routes         → utilities, errors
|
*/

/*
|--------------------------------------------------------------------------
| LANDING PAGE (Guest)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('landing')->middleware('guest.redirect');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Guest Only – redirect if authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware('guest.redirect')->group(function () {
    // Login
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);

    // Register
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Forgot Password (UI only)
    Route::get('/forgot-password', fn () => view('auth.forgot-password'))->name('forgot-password');
});

/*
|--------------------------------------------------------------------------
| LOGOUT (Authenticated Only)
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| SHARED / UTILITY ROUTES (Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/monitoring', function () {
        return view('monitoring.index');
    })->name('monitoring');

    Route::get('/notifications', function () {
        return view('notifications.index');
    })->name('notifications');

    Route::get('/visualization', function () {
        return view('dashboard.visualization.index');
    })->name('visualization');

    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');

    Route::get('/countries/detail', fn () => view('countries.show'))->name('countries.detail');
    Route::get('/dashboard/export/country/{id}', [\App\Http\Controllers\ReportExportController::class, 'exportCountryReport'])->name('report.export.country');
});

/*
|--------------------------------------------------------------------------
| AUTH UTILITY PAGES
|--------------------------------------------------------------------------
*/
Route::get('/reset-password',   fn () => view('auth.reset-password'))->name('reset-password');
Route::get('/verify-email',     fn () => view('auth.verify-email'))->name('verify-email');
Route::get('/session-expired',  fn () => view('auth.session-expired'))->name('session-expired');

/*
|--------------------------------------------------------------------------
| ERROR PAGES
|--------------------------------------------------------------------------
*/
Route::get('/403', fn () => view('errors.403'))->name('403');
Route::get('/404', fn () => view('errors.404'))->name('404');

/*
|--------------------------------------------------------------------------
| LEGACY ALIASES (Backward Compatibility for Views using old route names)
|
| Registered before module files so module files (admin.php, user.php)
| take precedence for URL routing, but named aliases (countries, admin, dashboard)
| remain defined in the named route collection.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/countries',  [\App\Http\Controllers\User\UserController::class, 'countries'])->name('countries');
    Route::get('/weather',    [\App\Http\Controllers\User\UserController::class, 'weather'])->name('weather');
    Route::get('/currency',   [\App\Http\Controllers\User\UserController::class, 'currency'])->name('currency');
    Route::get('/ports',      [\App\Http\Controllers\User\UserController::class, 'ports'])->name('ports');
    Route::get('/news',       [\App\Http\Controllers\User\UserController::class, 'news'])->name('news');
    Route::get('/risk',       [\App\Http\Controllers\User\UserController::class, 'risk'])->name('risk');
    Route::get('/comparison', [\App\Http\Controllers\User\UserController::class, 'comparison'])->name('comparison');
    Route::get('/watchlist',  [\App\Http\Controllers\User\UserController::class, 'favorite'])->name('watchlist');
    Route::get('/simulation', [\App\Http\Controllers\User\UserController::class, 'simulation'])->name('simulation');
});

/*
|--------------------------------------------------------------------------
| DESIGN SYSTEM (Dev Only)
|--------------------------------------------------------------------------
*/
Route::get('/design-system', fn () => view('design-system.index'))->name('design-system');

/*
|--------------------------------------------------------------------------
| MODULE ROUTE FILES (Loaded LAST so they overwrite legacy alias matching)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
