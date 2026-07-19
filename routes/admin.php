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

        /*
         |--------------------------------------------------------------
         | Port Dataset Management
         |--------------------------------------------------------------
         */
        Route::get('/ports', [AdminController::class, 'ports'])->name('ports');

        /*
         |--------------------------------------------------------------
         | Article Management
         |--------------------------------------------------------------
         */
        Route::get('/articles',              [AdminController::class, 'articles'])->name('articles');
        Route::get('/articles/create',       [AdminController::class, 'articleCreate'])->name('articles.create');
        Route::get('/articles/{id}',         [AdminController::class, 'articleDetail'])->name('articles.detail');
        Route::get('/articles/{id}/edit',    [AdminController::class, 'articleEdit'])->name('articles.edit');

    });
