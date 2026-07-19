<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Daftar seluruh Service Binding (Interface => Implementasi).
     *
     * @var array<string, string>
     */
    protected array $bindingsMap = [
        \App\Services\Contracts\AuthenticationServiceInterface::class => \App\Services\Implementations\AuthenticationService::class,
        \App\Services\Contracts\CountryServiceInterface::class => \App\Services\Implementations\CountryService::class,
        \App\Services\Contracts\RegionServiceInterface::class => \App\Services\Implementations\RegionService::class,
        \App\Services\Contracts\CurrencyServiceInterface::class => \App\Services\Implementations\CurrencyService::class,
        \App\Services\Contracts\LanguageServiceInterface::class => \App\Services\Implementations\LanguageService::class,
        \App\Services\Contracts\PortServiceInterface::class => \App\Services\Implementations\PortService::class,
        \App\Services\Contracts\WeatherServiceInterface::class => \App\Services\Implementations\WeatherService::class,
        \App\Services\Contracts\ExchangeRateServiceInterface::class => \App\Services\Implementations\ExchangeRateService::class,
        \App\Services\Contracts\NewsServiceInterface::class => \App\Services\Implementations\NewsService::class,
        \App\Services\Contracts\RiskServiceInterface::class => \App\Services\Implementations\RiskService::class,
        \App\Services\Contracts\RiskCalculatorServiceInterface::class => \App\Services\Implementations\RiskCalculatorService::class,
        \App\Services\Contracts\RiskHistoryServiceInterface::class => \App\Services\Implementations\RiskHistoryService::class,
        \App\Services\Contracts\SentimentServiceInterface::class => \App\Services\Implementations\SentimentService::class,
        \App\Services\Contracts\DictionaryServiceInterface::class => \App\Services\Implementations\DictionaryService::class,
        \App\Services\Contracts\WatchlistServiceInterface::class => \App\Services\Implementations\WatchlistService::class,
        \App\Services\Contracts\NotificationServiceInterface::class => \App\Services\Implementations\NotificationService::class,
        \App\Services\Contracts\DashboardServiceInterface::class => \App\Services\Implementations\DashboardService::class,
        \App\Services\Contracts\AnalyticsServiceInterface::class => \App\Services\Implementations\AnalyticsService::class,
        \App\Services\Contracts\ReportServiceInterface::class => \App\Services\Implementations\ReportService::class,
        \App\Services\Contracts\ApiLogServiceInterface::class => \App\Services\Implementations\ApiLogService::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        foreach ($this->bindingsMap as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
