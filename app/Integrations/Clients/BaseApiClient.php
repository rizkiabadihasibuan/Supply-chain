<?php

namespace App\Integrations\Clients;

use App\Integrations\Contracts\ApiClientInterface;
use App\Integrations\Exceptions\ApiException;
use App\Integrations\Support\ApiLogger;
use App\Integrations\Support\ApiRetryPolicy;
use App\Integrations\Support\ApiTimeoutPolicy;
use App\Integrations\Support\ApiAuthentication;
use App\Integrations\Support\ApiCacheManager;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

abstract class BaseApiClient extends \App\Services\BaseApiService implements ApiClientInterface
{
    protected $logger;
    protected $retryPolicy;
    protected $timeoutPolicy;
    protected $cacheManager;
    protected $authentication;

    public function __construct(
        ApiLogger $logger,
        ApiRetryPolicy $retryPolicy,
        ApiTimeoutPolicy $timeoutPolicy,
        ApiCacheManager $cacheManager,
        ApiAuthentication $authentication = null
    ) {
        $this->logger = $logger;
        $this->retryPolicy = $retryPolicy;
        $this->timeoutPolicy = $timeoutPolicy;
        $this->cacheManager = $cacheManager;
        $this->authentication = $authentication;
    }

    abstract protected function getBaseUrl(): string;

    protected function buildRequest(): PendingRequest
    {
        $request = Http::baseUrl($this->getBaseUrl())
            ->timeout($this->timeoutPolicy->getDefaultTimeout())
            ->acceptJson()
            ->retry(
                ApiRetryPolicy::MAX_RETRIES,
                ApiRetryPolicy::RETRY_DELAY_MS,
                function ($exception, $request) {
                    $status = isset($exception->response) ? $exception->response->status() : 500;
                    $shouldRetry = $this->retryPolicy->shouldRetry($status);
                    if ($shouldRetry) {
                        $method = method_exists($request, 'method') ? $request->method() : 'HTTP';
                        $url = method_exists($request, 'url') ? $request->url() : '';
                        $this->logger->logError($method, $url, $exception->getMessage(), ApiRetryPolicy::MAX_RETRIES);
                    }
                    return $shouldRetry;
                }
            );

        // Disable SSL verification for non-production environments
        if (! app()->environment('production')) {
            $request->withoutVerifying();
        }

        if ($this->authentication) {
            $request->withHeaders($this->authentication->getHeaders());
        }

        return $request;
    }

    public function get(string $endpoint, array $query = []): array
    {
        return $this->executeRequest('get', $endpoint, $query);
    }

    public function post(string $endpoint, array $data = []): array
    {
        return $this->executeRequest('post', $endpoint, $data);
    }

    public function put(string $endpoint, array $data = []): array
    {
        return $this->executeRequest('put', $endpoint, $data);
    }

    public function delete(string $endpoint, array $data = []): array
    {
        return $this->executeRequest('delete', $endpoint, $data);
    }

    protected function executeRequest(string $method, string $endpoint, array $payload = []): array
    {
        $startTime = microtime(true);
        $fullUrl = $this->getBaseUrl() . $endpoint;
        $this->logger->logRequest($method, $fullUrl, $payload);

        try {
            $response = $this->buildRequest()->$method($endpoint, $payload);
            $responseTime = (microtime(true) - $startTime) * 1000;

            $this->logger->logResponse($method, $fullUrl, $responseTime, $response->status(), $response->body());

            // Check for HTTP errors
            if ($response->failed()) {
                $statusCode = $response->status();
                $errorBody = $response->body();
                
                // Log specific error types
                $this->logHttpError($method, $fullUrl, $statusCode, $errorBody, $responseTime);
                
                throw new ApiException("API Request Failed: HTTP {$statusCode}", $statusCode, $errorBody);
            }

            // Check for empty response
            if (empty($response->body())) {
                \Illuminate\Support\Facades\Log::error('API returned empty response', [
                    'method' => $method,
                    'endpoint' => $fullUrl,
                    'status' => $response->status(),
                    'duration_ms' => round($responseTime, 2)
                ]);
                throw new ApiException("API returned empty response", 500);
            }

            // Try to parse JSON
            try {
                $jsonData = $response->json();
                if ($jsonData === null) {
                    throw new \Exception("Invalid JSON response");
                }
                return $jsonData ?? [];
            } catch (\Exception $jsonError) {
                \Illuminate\Support\Facades\Log::error('Invalid JSON response from API', [
                    'method' => $method,
                    'endpoint' => $fullUrl,
                    'status' => $response->status(),
                    'body' => substr($response->body(), 0, 500),
                    'error' => $jsonError->getMessage(),
                    'duration_ms' => round($responseTime, 2)
                ]);
                throw new ApiException("Invalid JSON response from API", 500, $response->body());
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $responseTime = (microtime(true) - $startTime) * 1000;
            \Illuminate\Support\Facades\Log::error('Connection timeout or network error', [
                'method' => $method,
                'endpoint' => $fullUrl,
                'error' => $e->getMessage(),
                'duration_ms' => round($responseTime, 2),
                'status' => 'connection_error'
            ]);
            if ($e instanceof ApiException) throw $e;
            throw new ApiException("Connection timeout or network error: " . $e->getMessage(), 503);

        } catch (\Exception $e) {
            $responseTime = (microtime(true) - $startTime) * 1000;
            $this->logger->logError($method, $fullUrl, $e->getMessage());
            if ($e instanceof ApiException) throw $e;
            
            \Illuminate\Support\Facades\Log::error('Unexpected API error', [
                'method' => $method,
                'endpoint' => $fullUrl,
                'error' => $e->getMessage(),
                'duration_ms' => round($responseTime, 2)
            ]);
            throw new ApiException($e->getMessage(), 500);
        }
    }

    /**
     * Log specific HTTP errors
     */
    protected function logHttpError(string $method, string $endpoint, int $statusCode, string $body, float $duration): void
    {
        $errorType = match($statusCode) {
            404 => 'Not Found',
            429 => 'Too Many Requests (Rate Limited)',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            default => "HTTP Error {$statusCode}"
        };

        \Illuminate\Support\Facades\Log::error("REST API Error: {$errorType}", [
            'method' => $method,
            'endpoint' => $endpoint,
            'status' => $statusCode,
            'error_type' => $errorType,
            'body' => substr($body, 0, 500),
            'duration_ms' => round($duration, 2)
        ]);
    }
}