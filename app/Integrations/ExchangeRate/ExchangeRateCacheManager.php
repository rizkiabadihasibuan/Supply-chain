<?php

namespace App\Integrations\ExchangeRate;

use Illuminate\Support\Facades\Cache;
use App\Integrations\Support\ApiLogger;

class ExchangeRateCacheManager
{
    protected $logger;
    protected $ttl;

    public function __construct(ApiLogger $logger)
    {
        $this->logger = $logger;
        $this->ttl = 1800; // 30 mins TTL standard
    }

    public function getCachedRates(string $base, callable $apiCall, bool $forceRefresh = false)
    {
        $key = "exchange_rate_{$base}";

        if ($forceRefresh) {
            Cache::forget($key);
            $this->logger->logRequest('CACHE', 'Force Refresh Exchange Rate for key: ' . $key);
        }

        return Cache::remember($key, $this->ttl, function() use ($apiCall, $key) {
            $this->logger->logRequest('CACHE_MISS', "Fetching fresh Exchange Rate data for key: $key");
            return $apiCall();
        });
    }
}