<?php

namespace App\Integrations\OpenMeteo;

use Illuminate\Support\Facades\Cache;
use App\Integrations\Support\ApiLogger;

class WeatherCacheManager
{
    protected $logger;
    protected $ttl;

    public function __construct(ApiLogger $logger)
    {
        $this->logger = $logger;
        $this->ttl = config('api.cache_ttl', 3600); // Default 1 hour
    }

    public function getCachedForecast(float $lat, float $lon, callable $apiCall, bool $forceRefresh = false)
    {
        $key = "weather_forecast_{$lat}_{$lon}";

        if ($forceRefresh) {
            Cache::forget($key);
            $this->logger->logRequest('CACHE', 'Force Refresh triggered for key: ' . $key);
        }

        if (Cache::has($key)) {
            $this->logger->logRequest('CACHE_HIT', "Data found in cache for key: $key");
        } else {
            $this->logger->logRequest('CACHE_MISS', "Fetching fresh data for key: $key");
        }

        return Cache::remember($key, $this->ttl, $apiCall);
    }
}