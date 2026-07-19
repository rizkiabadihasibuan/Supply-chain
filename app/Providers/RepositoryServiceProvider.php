<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Daftar seluruh Repository Binding (Interface => Implementasi).
     *
     * @var array<string, string>
     */
    protected array $bindingsMap = [
        \App\Repositories\Interfaces\UserRepositoryInterface::class => \App\Repositories\Implementations\UserRepository::class,
        \App\Repositories\Interfaces\RoleRepositoryInterface::class => \App\Repositories\Implementations\RoleRepository::class,
        \App\Repositories\Interfaces\CountryRepositoryInterface::class => \App\Repositories\Implementations\CountryRepository::class,
        \App\Repositories\Interfaces\RegionRepositoryInterface::class => \App\Repositories\Implementations\RegionRepository::class,
        \App\Repositories\Interfaces\CurrencyRepositoryInterface::class => \App\Repositories\Implementations\CurrencyRepository::class,
        \App\Repositories\Interfaces\LanguageRepositoryInterface::class => \App\Repositories\Implementations\LanguageRepository::class,
        \App\Repositories\Interfaces\PortRepositoryInterface::class => \App\Repositories\Implementations\PortRepository::class,
        \App\Repositories\Interfaces\WeatherRepositoryInterface::class => \App\Repositories\Implementations\WeatherRepository::class,
        \App\Repositories\Interfaces\NewsCacheRepositoryInterface::class => \App\Repositories\Implementations\NewsCacheRepository::class,
        \App\Repositories\Interfaces\ApiLogRepositoryInterface::class => \App\Repositories\Implementations\ApiLogRepository::class,
        \App\Repositories\Interfaces\NewsRepositoryInterface::class => \App\Repositories\Implementations\NewsRepository::class,
        \App\Repositories\Interfaces\ArticleRepositoryInterface::class => \App\Repositories\Implementations\ArticleRepository::class,
        \App\Repositories\Interfaces\CategoryRepositoryInterface::class => \App\Repositories\Implementations\CategoryRepository::class,
        \App\Repositories\Interfaces\SourceRepositoryInterface::class => \App\Repositories\Implementations\SourceRepository::class,
        \App\Repositories\Interfaces\RiskRepositoryInterface::class => \App\Repositories\Implementations\RiskRepository::class,
        \App\Repositories\Interfaces\RiskHistoryRepositoryInterface::class => \App\Repositories\Implementations\RiskHistoryRepository::class,
        \App\Repositories\Interfaces\RiskWeightRepositoryInterface::class => \App\Repositories\Implementations\RiskWeightRepository::class,
        \App\Repositories\Interfaces\SentimentRepositoryInterface::class => \App\Repositories\Implementations\SentimentRepository::class,
        \App\Repositories\Interfaces\DictionaryRepositoryInterface::class => \App\Repositories\Implementations\DictionaryRepository::class,
        \App\Repositories\Interfaces\WatchlistRepositoryInterface::class => \App\Repositories\Implementations\WatchlistRepository::class,
        \App\Repositories\Interfaces\NotificationRepositoryInterface::class => \App\Repositories\Implementations\NotificationRepository::class,
        \App\Repositories\Interfaces\SettingRepositoryInterface::class => \App\Repositories\Implementations\SettingRepository::class,
        \App\Repositories\Interfaces\AuditRepositoryInterface::class => \App\Repositories\Implementations\AuditRepository::class,
        \App\Repositories\Interfaces\RiskSnapshotRepositoryInterface::class => \App\Repositories\Implementations\RiskSnapshotRepository::class,
        \App\Repositories\Interfaces\RiskAlertRepositoryInterface::class => \App\Repositories\Implementations\RiskAlertRepository::class,
        \App\Repositories\Interfaces\RiskTrendRepositoryInterface::class => \App\Repositories\Implementations\RiskTrendRepository::class,
        \App\Repositories\Interfaces\RiskAggregationRepositoryInterface::class => \App\Repositories\Implementations\RiskAggregationRepository::class,
        \App\Repositories\Interfaces\RiskCalculatorRepositoryInterface::class => \App\Repositories\Implementations\RiskCalculatorRepository::class,
        \App\Repositories\Interfaces\RiskClassificationRepositoryInterface::class => \App\Repositories\Implementations\RiskClassificationRepository::class,
        \App\Repositories\Interfaces\DashboardAnalyticsRepositoryInterface::class => \App\Repositories\Implementations\DashboardAnalyticsRepository::class,
        \App\Repositories\Interfaces\RiskRankingRepositoryInterface::class => \App\Repositories\Implementations\RiskRankingRepository::class,
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
