<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Country Routes
    Route::get('/countries/{code}', [\App\Http\Controllers\CountryController::class, 'detail'])->name('countries.detail');
    Route::post('/countries/{code}/sync', [\App\Http\Controllers\CountryController::class, 'sync'])->name('countries.sync');

    // Comparison Routes
    Route::get('/compare', [\App\Http\Controllers\ComparisonController::class, 'index'])->name('compare');

    // Admin Specific Routes (Protected by RoleMiddleware)
    Route::middleware('role:Admin')->group(function () {
        Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/users/{user}/role', [\App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('admin.users.role');
        Route::post('/admin/ports', [\App\Http\Controllers\AdminController::class, 'storePort'])->name('admin.ports.store');
        Route::delete('/admin/ports/{port}', [\App\Http\Controllers\AdminController::class, 'destroyPort'])->name('admin.ports.destroy');
        Route::post('/admin/articles', [\App\Http\Controllers\AdminController::class, 'storeArticle'])->name('admin.articles.store');
        Route::delete('/admin/articles/{article}', [\App\Http\Controllers\AdminController::class, 'destroyArticle'])->name('admin.articles.destroy');
    });
});
