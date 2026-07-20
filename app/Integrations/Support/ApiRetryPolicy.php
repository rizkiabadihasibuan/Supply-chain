<?php

namespace App\Integrations\Support;

class ApiRetryPolicy
{
    public const MAX_RETRIES = 1;
    public const RETRY_DELAY_MS = 500;

    public function getRetryableStatusCodes(): array
    {
        return [429, 500, 502, 503, 504];
    }
    
    public function shouldRetry(int $status): bool
    {
        return in_array($status, $this->getRetryableStatusCodes());
    }
}