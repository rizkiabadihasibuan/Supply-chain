<?php

use App\Http\Controllers\Api\CountryApiController;
use App\Http\Controllers\Api\WeatherApiController;
use App\Http\Controllers\Api\CurrencyApiController;
use App\Http\Controllers\Api\PortApiController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\RiskApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/countries', [CountryApiController::class, 'index'])->name('api.countries');
Route::get('/weather', [WeatherApiController::class, 'index'])->name('api.weather');
Route::get('/currency', [CurrencyApiController::class, 'index'])->name('api.currency');
Route::get('/ports', [PortApiController::class, 'index'])->name('api.ports');
Route::get('/news', [NewsApiController::class, 'index'])->name('api.news');
Route::get('/risk', [RiskApiController::class, 'index'])->name('api.risk');
