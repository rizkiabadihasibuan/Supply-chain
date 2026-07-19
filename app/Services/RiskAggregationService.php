<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Country;
use App\DTOs\RiskAggregationDTO;
use App\Mappers\RiskAggregationMapper;
use App\Repositories\Interfaces\RiskAggregationRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RiskAggregationService
{
    protected CountryService $countryService;
    protected WeatherService $weatherService;
    protected ExchangeRateService $exchangeRateService;
    protected WorldBankService $worldBankService;
    protected NewsService $newsService;
    protected PortService $portService;
    protected RiskAggregationRepositoryInterface $repository;
    protected RiskAggregationMapper $mapper;

    public function __construct(
        CountryService $countryService,
        WeatherService $weatherService,
        ExchangeRateService $exchangeRateService,
        WorldBankService $worldBankService,
        NewsService $newsService,
        PortService $portService,
        RiskAggregationRepositoryInterface $repository,
        RiskAggregationMapper $mapper
    ) {
        $this->countryService = $countryService;
        $this->weatherService = $weatherService;
        $this->exchangeRateService = $exchangeRateService;
        $this->worldBankService = $worldBankService;
        $this->newsService = $newsService;
        $this->portService = $portService;
        $this->repository = $repository;
        $this->mapper = $mapper;
    }

    /**
     * Aggregate all risk data sources for a country.
     *
     * @param Country $country
     * @param bool $forceRefresh
     * @return RiskAggregationDTO
     */
    public function aggregateForCountry(Country $country, bool $forceRefresh = false): RiskAggregationDTO
    {
        $startTime = microtime(true);
        $cacheKey = "risk_aggregation_country_" . strtoupper($country->code);

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($country, $startTime) {
            $successCount = 0;
            $failCount = 0;

            // 1. Load Weather
            $weather = null;
            try {
                $lat = $country->coordinates ? $country->coordinates->latitude : 0.0;
                $lon = $country->coordinates ? $country->coordinates->longitude : 0.0;
                $weather = $this->weatherService->getWeather($lat, $lon, $country->timezone ?: 'UTC');
                $successCount++;
            } catch (\Throwable $e) {
                $failCount++;
                Log::error("RiskAggregation: Weather failed for {$country->code}: " . $e->getMessage());
            }

            // 2. Load Exchange Rate
            $exchangeRate = null;
            try {
                $base = $country->currency ? $country->currency->code : 'USD';
                $exchangeRate = $this->exchangeRateService->getLatest($base);
                $successCount++;
            } catch (\Throwable $e) {
                $failCount++;
                Log::error("RiskAggregation: ExchangeRate failed for {$country->code}: " . $e->getMessage());
            }

            // 3. Load Economic indicators
            $economic = null;
            try {
                $economic = $this->worldBankService->getEconomicData($country->code);
                $successCount++;
            } catch (\Throwable $e) {
                $failCount++;
                Log::error("RiskAggregation: WorldBank failed for {$country->code}: " . $e->getMessage());
            }

            // 4. Load News
            $news = [];
            try {
                $news = $this->newsService->getRiskIntelligenceNews(strtolower($country->code));
                $successCount++;
            } catch (\Throwable $e) {
                $failCount++;
                Log::error("RiskAggregation: News failed for {$country->code}: " . $e->getMessage());
            }

            // 5. Load Port
            $ports = [];
            try {
                $ports = $this->portService->searchPorts('', $country->name);
                $successCount++;
            } catch (\Throwable $e) {
                $failCount++;
                Log::error("RiskAggregation: Port failed for {$country->code}: " . $e->getMessage());
            }

            // Normalize & map to DTO
            $dto = $this->mapper->map($country, $weather, $exchangeRate, $economic, $news, $ports);

            // Persist to Database (risk_snapshots)
            $snapshot = $this->repository->saveSnapshot($country->id, $dto);

            $duration = (microtime(true) - $startTime) * 1000;
            Log::info("RiskAggregation: Completed for {$country->code}", [
                'country' => $country->code,
                'duration_ms' => round($duration, 2),
                'success_apis' => $successCount,
                'failed_apis' => $failCount,
                'snapshot_id' => $snapshot->id,
            ]);

            return $dto;
        });
    }

    /**
     * Clear aggregation cache for a country.
     */
    public function clearCache(Country $country): void
    {
        Cache::forget("risk_aggregation_country_" . strtoupper($country->code));
    }
}
