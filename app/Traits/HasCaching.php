<?php
namespace App\Traits;
use Illuminate\Support\Facades\Cache;
/**
 * HasCaching – Trait untuk caching API responses
 * Gunakan pada Services yang memanggil external API
 */
trait HasCaching {
    protected function remember(string $key, int $ttl, callable $callback): mixed {
        return Cache::remember($key, $ttl, $callback);
    }
    protected function forget(string $key): void {
        Cache::forget($key);
    }
}
