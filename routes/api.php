<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::middleware('auth')->group(function () {
    Route::get('/countries', [ApiController::class, 'countries'])->name('api.countries');
    Route::get('/risk', [ApiController::class, 'risk'])->name('api.risk');
    Route::get('/ports', [ApiController::class, 'ports'])->name('api.ports');
    Route::get('/news', [ApiController::class, 'news'])->name('api.news');
    Route::get('/currency', [ApiController::class, 'currency'])->name('api.currency');
    
    // Watchlist Routes
    Route::get('/watchlist', [ApiController::class, 'getWatchlist'])->name('api.watchlist');
    Route::post('/watchlist/toggle', [ApiController::class, 'toggleWatchlist'])->name('api.watchlist.toggle');
});
