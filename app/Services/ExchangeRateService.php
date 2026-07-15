<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    /**
     * Fetch the exchange rates (USD base).
     * Caches response for 24 hours (86400 seconds) and logs requests.
     *
     * @return array|null
     */
    public function fetchRates(): ?array
    {
        $cacheKey = "exchange_rates_usd_base";

        return Cache::remember($cacheKey, 86400, function () {
            $endpoint = "https://open.er-api.com/v6/latest/USD";
            $startTime = microtime(true);
            $responseStatus = null;

            try {
                $response = Http::withoutVerifying()->timeout(10)->get($endpoint);
                $responseStatus = $response->status();
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);

                // Log the API call
                $this->logApiCall($endpoint, $responseStatus, $executionTime);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['rates'])) {
                        return $data['rates'];
                    }
                }

                Log::warning("ExchangeRate API returned status code {$responseStatus}.");
                return null;

            } catch (\Exception $e) {
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);

                $this->logApiCall($endpoint, 500, $executionTime);
                Log::error("Failed to connect to ExchangeRate API: " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get a specific currency's exchange rate relative to USD.
     *
     * @param string $currencyCode
     * @return float|null
     */
    public function getRateAgainstUsd(string $currencyCode): ?float
    {
        $currencyCode = strtoupper(trim($currencyCode));
        if ($currencyCode === 'USD') {
            return 1.0;
        }

        $rates = $this->fetchRates();
        if ($rates && isset($rates[$currencyCode])) {
            return (float) $rates[$currencyCode];
        }

        return null;
    }

    /**
     * Log API request details to activity logs.
     *
     * @param string $endpoint
     * @param int $status
     * @param float $executionTime
     */
    protected function logApiCall(string $endpoint, int $status, float $executionTime): void
    {
        try {
            ActivityLog::create([
                'log_type' => 'api_request',
                'description' => "Panggilan ExchangeRate API untuk kurs mata uang",
                'metadata' => [
                    'api_name' => 'ExchangeRate API',
                    'endpoint' => $endpoint,
                    'response_status' => $status,
                    'execution_time' => $executionTime,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to write API log for ExchangeRate API: " . $e->getMessage());
        }
    }
}
