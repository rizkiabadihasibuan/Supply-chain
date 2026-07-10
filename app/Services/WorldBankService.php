<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WorldBankService
{
    /**
     * World Bank Indicators mapping.
     */
    protected const INDICATORS = [
        'gdp' => 'NY.GDP.MKTP.CD',
        'inflation' => 'FP.CPI.TOTL.ZG',
        'population' => 'SP.POP.TOTL',
        'export' => 'NE.EXP.GNFS.CD',
        'import' => 'NE.IMP.GNFS.CD',
    ];

    /**
     * Fetch a specific economic indicator for a country from World Bank API.
     * With 24-hour caching and request logging.
     *
     * @param string $countryCode (ISO-2)
     * @param string $metric ('gdp', 'inflation', 'population', 'export', 'import')
     * @return array|null
     */
    public function fetchIndicator(string $countryCode, string $metric): ?array
    {
        $countryCode = strtolower(trim($countryCode));
        $metric = strtolower(trim($metric));

        if (!array_key_exists($metric, self::INDICATORS)) {
            Log::error("Invalid economic metric requested: {$metric}");
            return null;
        }

        $indicatorCode = self::INDICATORS[$metric];
        $cacheKey = "world_bank_{$countryCode}_{$metric}";

        return Cache::remember($cacheKey, 86400, function () use ($countryCode, $indicatorCode, $metric) {
            // Get data for past 5 years to find the most recent non-null entry
            $endpoint = "http://api.worldbank.org/v2/country/{$countryCode}/indicator/{$indicatorCode}?date=2019:2024&format=json";
            $startTime = microtime(true);
            $responseStatus = null;

            try {
                $response = Http::timeout(10)->get($endpoint);
                $responseStatus = $response->status();
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);

                // Log the API request
                $this->logApiCall($endpoint, $responseStatus, $executionTime);

                if ($response->successful()) {
                    $data = $response->json();

                    // World Bank API returns an array: [0 => metadata, 1 => records]
                    if (is_array($data) && count($data) >= 2 && is_array($data[1])) {
                        return $this->parseRecentValue($data[1]);
                    }
                }

                Log::warning("World Bank API returned status code {$responseStatus} for country {$countryCode}, indicator {$indicatorCode}.");
                return null;

            } catch (\Exception $e) {
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);

                $this->logApiCall($endpoint, 500, $executionTime);
                Log::error("Failed to connect to World Bank API: " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Fetch all 5 key economic metrics for a country.
     *
     * @param string $countryCode
     * @return array
     */
    public function fetchAllMetrics(string $countryCode): array
    {
        $results = [];
        foreach (array_keys(self::INDICATORS) as $metric) {
            $data = $this->fetchIndicator($countryCode, $metric);
            $results[$metric] = $data['value'] ?? null;
            $results["{$metric}_year"] = $data['year'] ?? null;
        }
        return $results;
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
                'description' => "Panggilan World Bank API untuk endpoint " . parse_url($endpoint, PHP_URL_PATH),
                'metadata' => [
                    'api_name' => 'World Bank API',
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
     * Parse World Bank JSON records to get the most recent non-null value and its year.
     *
     * @param array $records
     * @return array|null
     */
    protected function parseRecentValue(array $records): ?array
    {
        // Loop from the first record (usually the most recent year)
        foreach ($records as $record) {
            if (isset($record['value']) && $record['value'] !== null) {
                return [
                    'year' => (int) $record['date'],
                    'value' => $record['value']
                ];
            }
        }
        return null;
    }
}
