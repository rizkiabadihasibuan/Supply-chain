<?php

namespace App\Integrations\GNews;

use Illuminate\Support\Facades\Cache;
use App\Integrations\Support\ApiLogger;

class NewsCacheManager
{
    protected $logger;
    protected $ttl;

    public function __construct(ApiLogger $logger)
    {
        $this->logger = $logger;
        $this->ttl = config('api.cache_ttl', 1800); // 30 mins standard for news
    }

    public function getCachedNews(string $type, string $identifier, callable $apiCall, bool $forceRefresh = false)
    {
        $key = "gnews_{$type}_{$identifier}";

        if ($forceRefresh) {
            Cache::forget($key);
            $this->logger->logRequest('CACHE', 'Force Refresh GNews Data for key: ' . $key);
        }

        return Cache::remember($key, $this->ttl, function() use ($apiCall, $key) {
            $this->logger->logRequest('CACHE_MISS', "Fetching fresh GNews data for key: $key");
            return $apiCall();
        });
    }
}