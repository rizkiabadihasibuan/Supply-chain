<?php

namespace App\Integrations\Support;

use Illuminate\Support\Facades\Log;

class ApiLogger
{
    public function logRequest(string $method, string $url, array $payload = []): void
    {
        Log::info("API Request [$method] $url", ['payload' => $payload]);
    }

    public function logResponse(string $method, string $url, float $responseTime, int $statusCode, $responseBody = null): void
    {
        Log::info("API Response [$method] $url", [
            'status' => $statusCode,
            'time_ms' => $responseTime,
            'body' => $responseBody
        ]);
    }

    public function logError(string $method, string $url, string $message, int $retryCount = 0): void
    {
        Log::error("API Error [$method] $url", [
            'message' => $message,
            'retry_count' => $retryCount
        ]);
    }
}