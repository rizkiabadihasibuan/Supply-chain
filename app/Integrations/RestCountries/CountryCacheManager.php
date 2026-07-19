<?php

namespace App\Integrations\RestCountries;

use Illuminate\Support\Facades\Cache;
use App\Integrations\Support\ApiLogger;
use Illuminate\Support\Facades\Log;

class CountryCacheManager
{
    protected $logger;
    protected $ttl;
    protected $backupTtl;

    public function __construct(ApiLogger $logger)
    {
        $this->logger = $logger;
        // Countries data is highly static. 7 days is a good default.
        $this->ttl = 7 * 24 * 60 * 60; // 7 days in seconds = 604800
        $this->backupTtl = null; // Indefinite backup
    }

    /**
     * Get cached all countries with fallback to backup cache
     */
    public function getCachedAll(callable $apiCall, bool $forceRefresh = false)
    {
        $key = 'rest_countries_all';
        $backupKey = 'rest_countries_all_backup';
        $startTime = microtime(true);

        if ($forceRefresh) {
            Cache::forget($key);
            Log::info('REST Countries API: Force refresh triggered for all countries');
        }

        // Check primary cache first
        if (Cache::has($key) && !$forceRefresh) {
            $duration = (microtime(true) - $startTime) * 1000;
            Log::info('REST Countries API cache hit', [
                'endpoint' => '/all',
                'type' => 'primary_cache',
                'duration_ms' => round($duration, 2),
                'status' => 'cache_hit'
            ]);
            return Cache::get($key);
        }

        // Try API call
        try {
            $startTime = microtime(true);
            $data = $apiCall();
            
            if (empty($data)) {
                throw new \Exception('API returned empty response');
            }

            // Cache successful response
            Cache::put($key, $data, now()->addSeconds($this->ttl));
            Cache::forever($backupKey, $data);
            
            $duration = (microtime(true) - $startTime) * 1000;
            Log::info('REST Countries API request success', [
                'endpoint' => '/all',
                'type' => 'api_call',
                'duration_ms' => round($duration, 2),
                'status' => 'success'
            ]);

            return $data;
        } catch (\Throwable $e) {
            $duration = (microtime(true) - $startTime) * 1000;
            
            // Try backup cache
            if (Cache::has($backupKey)) {
                Log::warning('REST Countries API failed, using backup cache', [
                    'endpoint' => '/all',
                    'type' => 'backup_cache',
                    'duration_ms' => round($duration, 2),
                    'status' => 'error',
                    'error' => $e->getMessage()
                ]);
                return Cache::get($backupKey);
            }

            // No backup available
            Log::error('REST Countries API failed and no backup cache available', [
                'endpoint' => '/all',
                'duration_ms' => round($duration, 2),
                'status' => 'error',
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Get cached country by code with fallback to backup cache
     */
    public function getCachedByCode(string $code, callable $apiCall, bool $forceRefresh = false)
    {
        $key = "rest_countries_code_" . strtolower($code);
        $backupKey = "rest_countries_code_backup_" . strtolower($code);
        $startTime = microtime(true);

        if ($forceRefresh) {
            Cache::forget($key);
            Log::info('REST Countries API: Force refresh triggered for code', ['code' => $code]);
        }

        // Check primary cache first
        if (Cache::has($key) && !$forceRefresh) {
            $duration = (microtime(true) - $startTime) * 1000;
            Log::info('REST Countries API cache hit', [
                'endpoint' => "/alpha/{$code}",
                'code' => $code,
                'type' => 'primary_cache',
                'duration_ms' => round($duration, 2),
                'status' => 'cache_hit'
            ]);
            return Cache::get($key);
        }

        // Try API call
        try {
            $startTime = microtime(true);
            $data = $apiCall();
            
            if (empty($data)) {
                throw new \Exception('API returned empty response');
            }

            // Cache successful response
            Cache::put($key, $data, now()->addSeconds($this->ttl));
            Cache::forever($backupKey, $data);
            
            $duration = (microtime(true) - $startTime) * 1000;
            Log::info('REST Countries API request success', [
                'endpoint' => "/alpha/{$code}",
                'code' => $code,
                'type' => 'api_call',
                'duration_ms' => round($duration, 2),
                'status' => 'success'
            ]);

            return $data;
        } catch (\Throwable $e) {
            $duration = (microtime(true) - $startTime) * 1000;
            
            // Try backup cache
            if (Cache::has($backupKey)) {
                Log::warning('REST Countries API failed, using backup cache', [
                    'endpoint' => "/alpha/{$code}",
                    'code' => $code,
                    'type' => 'backup_cache',
                    'duration_ms' => round($duration, 2),
                    'status' => 'error',
                    'error' => $e->getMessage()
                ]);
                return Cache::get($backupKey);
            }

            // No backup available
            Log::error('REST Countries API failed and no backup cache available', [
                'endpoint' => "/alpha/{$code}",
                'code' => $code,
                'duration_ms' => round($duration, 2),
                'status' => 'error',
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Get cached countries by region with fallback to backup cache
     */
    public function getCachedByRegion(string $region, callable $apiCall, bool $forceRefresh = false)
    {
        $key = "rest_countries_region_" . strtolower($region);
        $backupKey = "rest_countries_region_backup_" . strtolower($region);
        $startTime = microtime(true);

        if ($forceRefresh) {
            Cache::forget($key);
            Log::info('REST Countries API: Force refresh triggered for region', ['region' => $region]);
        }

        // Check primary cache first
        if (Cache::has($key) && !$forceRefresh) {
            $duration = (microtime(true) - $startTime) * 1000;
            Log::info('REST Countries API cache hit', [
                'endpoint' => "/region/{$region}",
                'region' => $region,
                'type' => 'primary_cache',
                'duration_ms' => round($duration, 2),
                'status' => 'cache_hit'
            ]);
            return Cache::get($key);
        }

        // Try API call
        try {
            $startTime = microtime(true);
            $data = $apiCall();
            
            if (empty($data)) {
                throw new \Exception('API returned empty response');
            }

            // Cache successful response
            Cache::put($key, $data, now()->addSeconds($this->ttl));
            Cache::forever($backupKey, $data);
            
            $duration = (microtime(true) - $startTime) * 1000;
            Log::info('REST Countries API request success', [
                'endpoint' => "/region/{$region}",
                'region' => $region,
                'type' => 'api_call',
                'duration_ms' => round($duration, 2),
                'status' => 'success'
            ]);

            return $data;
        } catch (\Throwable $e) {
            $duration = (microtime(true) - $startTime) * 1000;
            
            // Try backup cache
            if (Cache::has($backupKey)) {
                Log::warning('REST Countries API failed, using backup cache', [
                    'endpoint' => "/region/{$region}",
                    'region' => $region,
                    'type' => 'backup_cache',
                    'duration_ms' => round($duration, 2),
                    'status' => 'error',
                    'error' => $e->getMessage()
                ]);
                return Cache::get($backupKey);
            }

            // No backup available
            Log::error('REST Countries API failed and no backup cache available', [
                'endpoint' => "/region/{$region}",
                'region' => $region,
                'duration_ms' => round($duration, 2),
                'status' => 'error',
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}
