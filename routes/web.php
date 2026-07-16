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
