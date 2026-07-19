<?php

namespace App\Integrations\Support;

use Illuminate\Support\Facades\RateLimiter;

class ApiRateLimiter
{
    public function attempt(string $key, int $maxAttempts, callable $callback)
    {
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            throw new \Exception("Rate limit exceeded for $key");
        }
        
        RateLimiter::hit($key);
        return $callback();
    }
}