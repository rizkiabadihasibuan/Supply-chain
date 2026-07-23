<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Controllers
use App\Http\Controllers\Api\AuthController;

// Core Controllers
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\PortController;
use App\Http\Controllers\Api\RiskController;
use App\Http\Controllers\Api\RiskHistoryController;
use App\Http\Controllers\Api\SentimentController;
use App\Http\Controllers\Api\DictionaryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\WatchlistController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ShippingSimulationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::get('/system/health', [\App\Http\Controllers\Api\SystemHealthController::class, 'index']);
    
    // PUBLIC ROUTES
    Route::post('/auth/register', [AuthController::class, 'register'])->middleware('throttle:6,1');
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,1');
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:3,1');

    // PROTECTED ROUTES
    Route::middleware(['auth:web'])->group(function () {
        
        // Auth User Profile & Logout
        Route::get('/auth/me', function (Request $request) {
            return new \App\Http\Resources\Authentication\UserResource($request->user());
        });
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::put('/auth/change-password', [AuthController::class, 'changePassword']);

        // DASHBOARD & ANALYTICS
        Route::get('/dashboard', [DashboardController::class, 'summary']);
        Route::get('/analytics', [AnalyticsController::class, 'index']);
        Route::get('/recommendations', [\App\Http\Controllers\Api\RecommendationController::class, 'index']);

        // COUNTRY MODULE
        Route::get('/countries/search', [CountryController::class, 'search']);
        Route::get('/countries/{id}/intelligence', [CountryController::class, 'fullIntelligence']);
        Route::apiResource('countries', CountryController::class)->only(['index', 'show']);

        // WEATHER MODULE
        Route::get('/weather', [WeatherController::class, 'index']);
        Route::get('/weather/{country}', [WeatherController::class, 'show']); // Assuming show returns by country
        
        // CURRENCY MODULE
        Route::get('/currencies', [CurrencyController::class, 'index']);
        Route::get('/exchange-rate', [CurrencyController::class, 'filter']);

        // NEWS MODULE
        Route::get('/news/search', [NewsController::class, 'search']);
        Route::get('/news', [NewsController::class, 'index']);
        Route::get('/news/{id}', [NewsController::class, 'show']);
        
        // PORT MODULE
        Route::get('/ports/search', [PortController::class, 'search']);
        Route::get('/ports', [PortController::class, 'index']);
        Route::get('/ports/{id}', [PortController::class, 'show']);

        // SHIPPING SIMULATION MODULE
        Route::get('/shipping/route', [ShippingSimulationController::class, 'calculateRoute']);

        // RISK MODULE
        Route::get('/risk/history', [RiskHistoryController::class, 'index']);
        Route::get('/risk/{country}/trend', [RiskController::class, 'trend']);
        Route::get('/risk/{country}/alerts', [RiskController::class, 'alerts']);
        Route::get('/risk', [RiskController::class, 'index']);
        Route::get('/risk/{country}', [RiskController::class, 'show']);
        Route::post('/risk/calculate', [RiskController::class, 'store']); // calculate logic

        // WATCHLIST MODULE
        Route::get('/watchlists', [WatchlistController::class, 'index']);
        Route::post('/watchlists', [WatchlistController::class, 'store']);
        Route::delete('/watchlists/{id}', [WatchlistController::class, 'destroy']);

        // COMPARISON MODULE
        Route::get('/comparison', [\App\Http\Controllers\Api\ComparisonController::class, 'index']);

        // NOTIFICATION MODULE
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::put('/notifications/read', [NotificationController::class, 'update']); // bulk read
        Route::put('/notifications/{id}/read', [NotificationController::class, 'update']); // single read

        // AI MODULE
        Route::post('/sentiment/analyze', [SentimentController::class, 'analyze']);
        Route::get('/sentiment/history', [SentimentController::class, 'index']); // History of sentiments

        // ANALYTICS MODULE
        Route::prefix('analytics')->middleware('throttle:60,1')->group(function () {
            Route::get('/overview', [\App\Http\Controllers\Api\DashboardAnalyticsController::class, 'overview']);
            Route::get('/global-summary', [\App\Http\Controllers\Api\DashboardAnalyticsController::class, 'globalSummary']);
            Route::get('/risk-distribution', [\App\Http\Controllers\Api\DashboardAnalyticsController::class, 'riskDistribution']);
            Route::get('/top-risk-countries', [\App\Http\Controllers\Api\DashboardAnalyticsController::class, 'topRiskCountries']);
            Route::get('/lowest-risk-countries', [\App\Http\Controllers\Api\DashboardAnalyticsController::class, 'lowestRiskCountries']);
            Route::get('/risk-trends', [\App\Http\Controllers\Api\DashboardAnalyticsController::class, 'riskTrends']);
            Route::get('/risk-ranking', [\App\Http\Controllers\Api\DashboardAnalyticsController::class, 'riskRanking']);
            Route::get('/alerts-summary', [\App\Http\Controllers\Api\DashboardAnalyticsController::class, 'alertsSummary']);
            Route::get('/weather-risk', [\App\Http\Controllers\Api\DashboardAnalyticsController::class, 'weatherRisk']);
            Route::get('/economic-risk', [\App\Http\Controllers\Api\DashboardAnalyticsController::class, 'economicRisk']);
            Route::get('/political-risk', [\App\Http\Controllers\Api\DashboardAnalyticsController::class, 'politicalRisk']);
            Route::get('/logistics-risk', [\App\Http\Controllers\Api\DashboardAnalyticsController::class, 'logisticsRisk']);
        });

        // ==========================================
        // ADMIN ONLY ROUTES
        // ==========================================
        Route::middleware(['admin'])->group(function () {
            
            // Country Data Management
            Route::apiResource('countries', CountryController::class)->only(['store', 'update', 'destroy']);
            
            // Sync & Refresh Tools
            Route::post('/weather/refresh', [WeatherController::class, 'refresh']);
            Route::post('/exchange-rate/refresh', [CurrencyController::class, 'refresh']);
            Route::post('/news/sync', [ArticleController::class, 'store']); // Syncing as store for articles
            
            // AI Settings
            Route::post('/dictionary/import', [DictionaryController::class, 'import']);
            
            // System Settings
            Route::get('/settings', [SettingController::class, 'index']);
            Route::put('/settings', [SettingController::class, 'update']);
        });

    });

});

// ==========================================
// REST API Internal (Non-prefixed aliases for specifications compliance)
// ==========================================
Route::middleware(['auth:web'])->group(function () {
    Route::get('/countries/search', [CountryController::class, 'search']);
    Route::get('/countries/{country}/coordinates', [CountryController::class, 'coordinates']);
    Route::get('/countries/{country}/currency', [CountryController::class, 'currency']);
    Route::get('/countries/{country}', [CountryController::class, 'show']);
    Route::get('/countries', [CountryController::class, 'index']);

    Route::get('/risk', [RiskController::class, 'index']);
    Route::get('/ports', [PortController::class, 'index']);
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/currency', [CurrencyController::class, 'index']);
    Route::get('/weather', [WeatherController::class, 'index']);
    Route::get('/weather/{country}', [WeatherController::class, 'show']);
    Route::get('/comparison', [\App\Http\Controllers\Api\ComparisonController::class, 'index']);
    Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'summary']);
    Route::get('/analytics', [\App\Http\Controllers\Api\AnalyticsController::class, 'index']);
    Route::get('/recommendations', [\App\Http\Controllers\Api\RecommendationController::class, 'index']);
});

