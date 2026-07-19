<?php

namespace App\Integrations\Support;

class ApiRetryPolicy
{
    public const MAX_RETRIES = 3;
    public const RETRY_DELAY_MS = 1000;

    public function getRetryableStatusCodes(): array
    {
        return [429, 500, 502, 503, 504];
    }
    
    public function shouldRetry(int $status): bool
    {
        return in_array($status, $this->getRetryableStatusCodes());
    }
}