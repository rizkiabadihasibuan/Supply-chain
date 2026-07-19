<?php

namespace App\Integrations\WorldPort;

use Illuminate\Support\Facades\Cache;
use App\Integrations\Support\ApiLogger;

class PortCacheManager
{
    protected $logger;
    protected $ttl;

    public function __construct(ApiLogger $logger)
    {
        $this->logger = $logger;
        // Port data is highly static. 7 days TTL standard.
        $this->ttl = config('api.cache_ttl', 604800); 
    }

    public function getCachedPorts(string $identifier, callable $apiCall, bool $forceRefresh = false)
    {
        $key = "world_port_{$identifier}";

        if ($forceRefresh) {
            Cache::forget($key);
            $this->logger->logRequest('CACHE', 'Force Refresh World Port Data for key: ' . $key);
        }

        return Cache::remember($key, $this->ttl, function() use ($apiCall, $key) {
            $this->logger->logRequest('CACHE_MISS', "Fetching fresh World Port data for key: $key");
            return $apiCall();
        });
    }
}