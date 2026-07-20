<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES – Authentication Flow
| routes/admin.php
|
| All Admin panel routes with:
| - Prefix:     /admin
| - Middleware:  auth, admin
| - Controller:  App\Http\Controllers\Admin\AdminController
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        /*
         |--------------------------------------------------------------
         | Dashboard Admin
         | URL: /admin/dashboard
         |--------------------------------------------------------------
         */
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        /*
         |--------------------------------------------------------------
         | User Management
         |--------------------------------------------------------------
         */
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        /*
         |--------------------------------------------------------------
         | Port Dataset Management
         |--------------------------------------------------------------
         */
        Route::get('/ports', [AdminController::class, 'ports'])->name('ports');
        Route::post('/ports', [AdminController::class, 'storePort'])->name('ports.store');
        Route::delete('/ports/{id}', [AdminController::class, 'destroyPort'])->name('ports.destroy');

        /*
         |--------------------------------------------------------------
         | Article Management
         |--------------------------------------------------------------
         */
        Route::get('/articles',              [AdminController::class, 'articles'])->name('articles');
        Route::get('/articles/create',       [AdminController::class, 'articleCreate'])->name('articles.create');
        Route::post('/articles',             [AdminController::class, 'storeArticle'])->name('articles.store');
        Route::delete('/articles/{id}',      [AdminController::class, 'destroyArticle'])->name('articles.destroy');

    });
