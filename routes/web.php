<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/countries', [DashboardController::class, 'countries'])->name('countries');
Route::get('/weather', [DashboardController::class, 'weather'])->name('weather');
Route::get('/currency', [DashboardController::class, 'currency'])->name('currency');
Route::get('/ports', [DashboardController::class, 'ports'])->name('ports');
Route::get('/news', [DashboardController::class, 'news'])->name('news');
Route::get('/risk', [DashboardController::class, 'risk'])->name('risk');
Route::get('/comparison', [DashboardController::class, 'comparison'])->name('comparison');
Route::get('/watchlist', [DashboardController::class, 'watchlist'])->name('watchlist');
Route::get('/admin', [DashboardController::class, 'admin'])->name('admin');

Route::get('/monitoring', function() {
    return view('monitoring.index');
})->name('monitoring');

Route::get('/notifications', function() {
    return view('notifications.index');
})->name('notifications');

Route::get('/login', function() {
    return view('auth.login');
})->name('login');

Route::get('/register', function() {
    return view('auth.register');
})->name('register');

Route::get('/forgot-password', function() {
    return view('auth.forgot-password');
})->name('forgot-password');

Route::get('/reset-password', function() {
    return view('auth.reset-password');
})->name('reset-password');

Route::get('/verify-email', function() {
    return view('auth.verify-email');
})->name('verify-email');

Route::get('/session-expired', function() {
    return view('auth.session-expired');
})->name('session-expired');

Route::get('/403', function() {
    return view('errors.403');
})->name('403');

Route::get('/404', function() {
    return view('errors.404');
})->name('404');Route::get('/profile', function() {
    return view('profile.index');
})->name('profile');

Route::get('/visualization', function() {
    return view('dashboard.visualization.index');
})->name('visualization');



Route::get('/countries/detail', function() {
    return view('countries.show');
})->name('countries.detail');

Route::get('/design-system', function() {
    return view('design-system.index');
})->name('design-system');

