<?php

namespace App\Services;

use App\Integrations\ExchangeRate\ExchangeRateApiClient;
use App\Integrations\ExchangeRate\ExchangeRateMapper;
use App\Integrations\ExchangeRate\ExchangeRateCacheManager;
use App\Integrations\ExchangeRate\ExchangeRateDTO;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    protected $apiClient;
    protected $mapper;
    protected $cacheManager;

    public function __construct(
        ExchangeRateApiClient $apiClient,
        ExchangeRateMapper $mapper,
        ExchangeRateCacheManager $cacheManager
    ) {
        $this->apiClient = $apiClient;
        $this->mapper = $mapper;
        $this->cacheManager = $cacheManager;
    }

    /**
     * Get Latest Exchange Rates
     */
    public function getLatest(string $base = 'USD', bool $forceRefresh = false): ExchangeRateDTO
    {
        $rawData = $this->cacheManager->getCachedRates($base, function () use ($base) {
            try {
                return $this->apiClient->getLatest($base);
            } catch (\Exception $e) {
                Log::error("ExchangeRate API fallback exception: " . $e->getMessage());
                throw $e;
            }
        }, $forceRefresh);

        return $this->mapper->map($rawData);
    }
}