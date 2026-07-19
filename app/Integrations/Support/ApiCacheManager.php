<?php

namespace App\Integrations\Support;

use Illuminate\Support\Facades\Cache;

class ApiCacheManager
{
    public function remember(string $key, int $ttl, callable $callback)
    {
        return Cache::remember($key, $ttl, $callback);
    }

    public function forget(string $key): void
    {
        Cache::forget($key);
    }
}