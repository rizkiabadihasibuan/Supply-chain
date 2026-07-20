<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Interfaces\DashboardAnalyticsRepositoryInterface;
use App\Mappers\DashboardAnalyticsMapper;
use App\DTOs\DashboardAnalyticsDTO;
use App\Exceptions\MissingRiskDataException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DashboardAnalyticsService
{
    protected DashboardAnalyticsRepositoryInterface $repo;
    protected DashboardAnalyticsMapper $mapper;

    public const REGISTRY_KEY = 'analytics_registered_keys';

    public function __construct(
        DashboardAnalyticsRepositoryInterface $repo,
        DashboardAnalyticsMapper $mapper
    ) {
        $this->repo = $repo;
        $this->mapper = $mapper;
    }

    /**
     * Generate standard cache key based on type and filters.
     */
    protected function getCacheKey(string $type, array $filters): string
    {
        return "analytics_" . $type . "_" . md5(serialize($filters));
    }

    /**
     * Add a key to the registry of generated analytics cache keys.
     */
    protected function registerCacheKey(string $key): void
    {
        $registry = Cache::get(self::REGISTRY_KEY, []);
        if (!in_array($key, $registry)) {
            $registry[] = $key;
            Cache::forever(self::REGISTRY_KEY, $registry);
        }
    }

    /**
     * Cache remember wrapper with registry tracking.
     */
    protected function remember(string $type, array $filters, \Closure $callback): DashboardAnalyticsDTO
    {
        $key = $this->getCacheKey($type, $filters);
        $this->registerCacheKey($key);

        $ttl = (int) Config::get('risk.cache_ttl', 3600);

        $startTime = microtime(true);
        $isHit = Cache::has($key);

        $result = Cache::remember($key, now()->addSeconds($ttl), $callback);

        $duration = (microtime(true) - $startTime) * 1000;
        Log::info("DashboardAnalyticsService: Analytics retrieved", [
            'analytics_type' => $type,
            'cache_status' => $isHit ? 'HIT' : 'MISS',
            'query_time_ms' => round($duration, 2),
        ]);

        return $result;
    }

    /**
     * Clear all registered analytics cache keys.
     */
    public function forget(): bool
    {
        $registry = Cache::get(self::REGISTRY_KEY, []);
        foreach ($registry as $key) {
            Cache::forget($key);
        }
        Cache::forget(self::REGISTRY_KEY);
        Log::info("DashboardAnalyticsService: Cleared all registered analytics cache.");
        return true;
    }

    /**
     * Refresh the entire analytics cache by pre-warming default views.
     */
    public function refresh(): void
    {
        $this->forget();

        // Warm cache with default empty filters
        $this->getOverview([]);
        $this->getGlobalSummary([]);
        $this->getRiskDistribution([]);
        $this->getTopRiskCountries([]);
        $this->getLowestRiskCountries([]);
        $this->getRiskTrends([]);
        $this->getRiskRanking([]);
        $this->getAlertsSummary([]);
        $this->getWeatherRisk([]);
        $this->getEconomicRisk([]);
        $this->getPoliticalRisk([]);
        $this->getLogisticsRisk([]);
    }

    /**
     * Helper to compute standard deviation of an array of float scores.
     */
    public function calculateStandardDeviation(array $numbers): float
    {
        $count = count($numbers);
        if ($count === 0) {
            return 0.0;
        }

        $mean = array_sum($numbers) / $count;
        $variance = 0.0;

        foreach ($numbers as $number) {
            $variance += pow($number - $mean, 2);
        }

        return (float) sqrt($variance / $count);
    }

    /**
     * Helper to compute median of an array of float scores.
     */
    public function calculateMedian(array $numbers): float
    {
        $count = count($numbers);
        if ($count === 0) {
            return 0.0;
        }

        sort($numbers);
        $middleIndex = (int) floor($count / 2);

        if ($count % 2 !== 0) {
            return (float) $numbers[$middleIndex];
        }

        return (float) (($numbers[$middleIndex - 1] + $numbers[$middleIndex]) / 2);
    }

    /**
     * 1. Overview Analytics
     */
    public function getOverview(array $filters = []): DashboardAnalyticsDTO
    {
        return $this->remember('overview', $filters, function () use ($filters) {
            $data = $this->repo->getOverviewData($filters);
            return $this->mapper->map('overview', $data);
        });
    }

    /**
     * 2. Global Summary Analytics
     */
    public function getGlobalSummary(array $filters = []): DashboardAnalyticsDTO
    {
        return $this->remember('global-summary', $filters, function () use ($filters) {
            $raw = $this->repo->getGlobalSummaryData($filters);
            $scores = $raw['scores'] ?? [];

            if (empty($scores)) {
                $scores = [0.0];
            }

            $count = count($scores);
            $avg = array_sum($scores) / $count;
            $max = max($scores);
            $min = min($scores);
            $median = $this->calculateMedian($scores);
            $stdDev = $this->calculateStandardDeviation($scores);

            return $this->mapper->map('global-summary', [
                'global_average_score' => (float) $avg,
                'highest_score' => (float) $max,
                'lowest_score' => (float) $min,
                'median_score' => (float) $median,
                'standard_deviation' => (float) $stdDev,
                'total_scores_count' => $count,
            ]);
        });
    }

    /**
     * 3. Risk Distribution
     */
    public function getRiskDistribution(array $filters = []): DashboardAnalyticsDTO
    {
        return $this->remember('risk-distribution', $filters, function () use ($filters) {
            $data = $this->repo->getRiskDistributionData($filters);
            return $this->mapper->map('risk-distribution', $data);
        });
    }

    /**
     * 4. Top Risk Countries
     */
    public function getTopRiskCountries(array $filters = []): DashboardAnalyticsDTO
    {
        return $this->remember('top-risk-countries', $filters, function () use ($filters) {
            $data = $this->repo->getTopRiskCountriesData($filters);
            return $this->mapper->map('top-risk-countries', $data);
        });
    }

    /**
     * 5. Lowest Risk Countries
     */
    public function getLowestRiskCountries(array $filters = []): DashboardAnalyticsDTO
    {
        return $this->remember('lowest-risk-countries', $filters, function () use ($filters) {
            $data = $this->repo->getLowestRiskCountriesData($filters);
            return $this->mapper->map('lowest-risk-countries', $data);
        });
    }

    /**
     * 6. Risk Trends
     */
    public function getRiskTrends(array $filters = []): DashboardAnalyticsDTO
    {
        return $this->remember('risk-trends', $filters, function () use ($filters) {
            $data = $this->repo->getRiskTrendsData($filters);
            return $this->mapper->map('risk-trends', $data);
        });
    }

    /**
     * 7. Risk Ranking
     */
    public function getRiskRanking(array $filters = []): DashboardAnalyticsDTO
    {
        return $this->remember('risk-ranking', $filters, function () use ($filters) {
            $data = $this->repo->getRiskRankingData($filters);
            return $this->mapper->map('risk-ranking', $data);
        });
    }

    /**
     * 8. Alerts Summary
     */
    public function getAlertsSummary(array $filters = []): DashboardAnalyticsDTO
    {
        return $this->remember('alerts-summary', $filters, function () use ($filters) {
            $data = $this->repo->getAlertsSummaryData($filters);
            return $this->mapper->map('alerts-summary', $data);
        });
    }

    /**
     * Component Risks Aggregate Calculator
     */
    protected function getComponentRisk(string $component, array $filters = []): DashboardAnalyticsDTO
    {
        return $this->remember($component . '-risk', $filters, function () use ($component, $filters) {
            $raw = $this->repo->getComponentRiskData($component, $filters);
            $scores = $raw['scores'] ?? [];

            if (empty($scores)) {
                throw new MissingRiskDataException("No risk scores data found for component '{$component}' calculation.");
            }

            $count = count($scores);
            $avg = array_sum($scores) / $count;
            $max = max($scores);
            $min = min($scores);
            $stdDev = $this->calculateStandardDeviation($scores);

            return $this->mapper->map($component . '-risk', [
                'component' => $component,
                'average_score' => (float) $avg,
                'highest_score' => (float) $max,
                'lowest_score' => (float) $min,
                'standard_deviation' => (float) $stdDev,
                'highest_country' => $raw['highest'] ?? null,
                'lowest_country' => $raw['lowest'] ?? null,
                'total_countries_count' => $count,
            ]);
        });
    }

    /**
     * 9. Weather Risk
     */
    public function getWeatherRisk(array $filters = []): DashboardAnalyticsDTO
    {
        return $this->getComponentRisk('weather', $filters);
    }

    /**
     * 10. Economic Risk
     */
    public function getEconomicRisk(array $filters = []): DashboardAnalyticsDTO
    {
        return $this->getComponentRisk('economic', $filters);
    }

    /**
     * 11. Political Risk
     */
    public function getPoliticalRisk(array $filters = []): DashboardAnalyticsDTO
    {
        return $this->getComponentRisk('political', $filters);
    }

    /**
     * 12. Logistics Risk
     */
    public function getLogisticsRisk(array $filters = []): DashboardAnalyticsDTO
    {
        return $this->getComponentRisk('logistics', $filters);
    }
}
