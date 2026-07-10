<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RestCountriesService
{
    /**
     * Fetch country details by ISO 3166-1 alpha-2 or alpha-3 code.
     * Includes caching and request logging.
     *
     * @param string $code
     * @return array|null
     */
    public function fetchByCode(string $code): ?array
    {
        $code = strtoupper(trim($code));
        $cacheKey = "rest_countries_{$code}";

        // Cache response for 24 hours (86400 seconds)
        return Cache::remember($cacheKey, 86400, function () use ($code) {
            $endpoint = "https://restcountries.com/v3.1/alpha/{$code}";
            $startTime = microtime(true);
            $responseStatus = null;

            try {
                // Call external API with a timeout
                $response = Http::timeout(10)->get($endpoint);
                $responseStatus = $response->status();
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2); // store in ms

                // Log the API call to activity logs
                $this->logApiCall($endpoint, $responseStatus, $executionTime);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (!empty($data) && is_array($data)) {
                        return $this->parseResponse($data[0]);
                    }
                }

                Log::warning("REST Countries API returned status code {$responseStatus} for code {$code}.");
                return null;

            } catch (\Exception $e) {
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);
                
                // Log failed API call with 500 status code
                $this->logApiCall($endpoint, 500, $executionTime);
                
                Log::error("Failed to connect to REST Countries API: " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Log API request details to database.
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
                'description' => "Panggilan REST Countries API untuk kode " . basename($endpoint),
                'metadata' => [
                    'api_name' => 'REST Countries API',
                    'endpoint' => $endpoint,
                    'response_status' => $status,
                    'execution_time' => $executionTime,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to write API log: " . $e->getMessage());
        }
    }

    /**
     * Parse REST Countries API raw JSON format into our platform's structure.
     *
     * @param array $countryData
     * @return array
     */
    protected function parseResponse(array $countryData): array
    {
        // Extract currency code & name
        $currencyCode = 'N/A';
        $currencyName = 'N/A';
        if (!empty($countryData['currencies']) && is_array($countryData['currencies'])) {
            $currencyCode = array_key_first($countryData['currencies']);
            $currencyName = $countryData['currencies'][$currencyCode]['name'] ?? 'N/A';
        }

        // Extract languages
        $languagesList = [];
        if (!empty($countryData['languages']) && is_array($countryData['languages'])) {
            $languagesList = array_values($countryData['languages']);
        }
        $language = !empty($languagesList) ? implode(', ', $languagesList) : 'N/A';

        return [
            'name' => $countryData['name']['common'] ?? 'N/A',
            'region' => $countryData['region'] ?? 'N/A',
            'population' => $countryData['population'] ?? 0,
            'currency_code' => $currencyCode,
            'currency_name' => $currencyName,
            'language' => $language,
            'flag_url' => $countryData['flags']['svg'] ?? ($countryData['flags']['png'] ?? null),
            'capital' => !empty($countryData['capital']) ? $countryData['capital'][0] : 'N/A',
        ];
    }
}
