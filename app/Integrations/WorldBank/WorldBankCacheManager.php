<?php

namespace App\Integrations\WorldBank;

use Illuminate\Support\Facades\Cache;
use App\Integrations\Support\ApiLogger;

class WorldBankCacheManager
{
    protected $logger;
    protected $ttl;

    public function __construct(ApiLogger $logger)
    {
        $this->logger = $logger;
        // Economic data rarely changes daily, so longer TTL makes sense.
        $this->ttl = config('api.cache_ttl', 86400); // 24 hours standard
    }

    public function getCachedIndicator(string $countryCode, callable $apiCall, bool $forceRefresh = false)
    {
        $key = "worldbank_indicators_{$countryCode}";

        if ($forceRefresh) {
            Cache::forget($key);
            $this->logger->logRequest('CACHE', 'Force Refresh WorldBank Data for key: ' . $key);
        }

        return Cache::remember($key, $this->ttl, function() use ($apiCall, $key) {
            $this->logger->logRequest('CACHE_MISS', "Fetching fresh WorldBank data for key: $key");
            return $apiCall();
        });
    }
}