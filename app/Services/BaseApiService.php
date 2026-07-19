<?php

declare(strict_types=1);

namespace App\Services;

use App\Integrations\Exceptions\ApiException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\PendingRequest;

abstract class BaseApiService
{
    protected string $baseUrl = '';
    protected int $timeout = 10;
    protected int $maxRetries = 3;
    protected int $retryDelay = 500;

    /**
     * Build HTTP Request Client
     */
    protected function buildRequest(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->acceptJson()
            ->retry($this->maxRetries, $this->retryDelay, function ($exception, $request) {
                $status = isset($exception->response) ? $exception->response->status() : 500;
                $shouldRetry = $status === 429 || $status >= 500;
                if ($shouldRetry) {
                    Log::warning("Retrying request to: " . $request->url() . " | Status: " . $status);
                }
                return $shouldRetry;
            });
    }

    /**
     * Execute Request with standard Logging, Timeout, and Exception handling
     */
    protected function execute(string $method, string $endpoint, array $payload = []): array
    {
        $startTime = microtime(true);
        $method = strtolower($method);

        Log::info("API Request: [{$method}] {$this->baseUrl}{$endpoint}", [
            'payload' => $payload,
            'request_time' => now()->toIso8601String()
        ]);

        try {
            $response = $this->buildRequest()->$method($endpoint, $payload);
            $duration = (microtime(true) - $startTime) * 1000;

            Log::info("API Response: Status {$response->status()} | Duration: " . round($duration, 2) . "ms", [
                'endpoint' => $endpoint,
            ]);

            if ($response->failed()) {
                throw new ApiException("HTTP Request Failed: Status " . $response->status(), $response->status(), $response->body());
            }

            return $response->json() ?? [];
        } catch (\Throwable $e) {
            $duration = (microtime(true) - $startTime) * 1000;
            Log::error("API Error: [{$method}] {$endpoint} | Duration: " . round($duration, 2) . "ms | Message: " . $e->getMessage(), [
                'exception' => get_class($e)
            ]);

            if ($e instanceof ApiException) {
                throw $e;
            }
            throw new ApiException($e->getMessage(), 500);
        }
    }
}
