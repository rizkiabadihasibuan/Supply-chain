<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenMeteoService
{
    /**
     * Fetch current weather for given coordinates.
     * Caches responses for 24 hours (86400 seconds) and logs requests.
     */
    public function fetchWeather(string $countryCode, float $latitude, float $longitude): ?array
    {
        $countryCode = strtoupper(trim($countryCode));
        $cacheKey = "open_meteo_{$countryCode}";

        return Cache::remember($cacheKey, 86400, function () use ($countryCode, $latitude, $longitude) {
            $endpoint = "https://api.open-meteo.com/v1/forecast?latitude={$latitude}&longitude={$longitude}&current=temperature_2m,precipitation,weather_code,wind_speed_10m,wind_gusts_10m&timezone=auto";
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
                    if (isset($data['current'])) {
                        return $this->parseResponse($data['current']);
                    }
                }

                Log::warning("Open-Meteo API returned status code {$responseStatus} for country {$countryCode} at coordinates [{$latitude}, {$longitude}].");

                return null;

            } catch (\Exception $e) {
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);

                $this->logApiCall($endpoint, 500, $executionTime);
                Log::error('Failed to connect to Open-Meteo API: '.$e->getMessage());

                return null;
            }
        });
    }

    /**
     * Log API request details to activity logs.
     */
    protected function logApiCall(string $endpoint, int $status, float $executionTime): void
    {
        try {
            ActivityLog::create([
                'log_type' => 'api_request',
                'description' => 'Panggilan Open-Meteo API untuk cuaca koordinat',
                'metadata' => [
                    'api_name' => 'Open-Meteo API',
                    'endpoint' => $endpoint,
                    'response_status' => $status,
                    'execution_time' => $executionTime,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to write API log for Open-Meteo: '.$e->getMessage());
        }
    }

    /**
     * Parse weather response and calculate storm risk.
     */
    protected function parseResponse(array $current): array
    {
        $temp = $current['temperature_2m'] ?? 0.0;
        $precipitation = $current['precipitation'] ?? 0.0;
        $windSpeed = $current['wind_speed_10m'] ?? 0.0;
        $windGusts = $current['wind_gusts_10m'] ?? 0.0;
        $weatherCode = $current['weather_code'] ?? 0;

        // Map WMO Weather Codes to string conditions
        $condition = $this->mapWeatherCode($weatherCode);

        // Calculate storm risk (0% - 100%)
        // High wind speed, high gusts, and heavy precipitation contribute to high storm risk.
        $stormRisk = 0.0;

        if ($windSpeed > 15.0) {
            $stormRisk += ($windSpeed - 15.0) * 1.5;
        }
        if ($windGusts > 30.0) {
            $stormRisk += ($windGusts - 30.0) * 2.0;
        }
        if ($precipitation > 2.0) {
            $stormRisk += $precipitation * 5.0;
        }

        // Clamp storm risk between 0 and 100
        $stormRisk = min(100.0, max(0.0, $stormRisk));

        return [
            'temp' => $temp,
            'precipitation' => $precipitation,
            'wind_speed' => $windSpeed,
            'wind_gusts' => $windGusts,
            'condition' => $condition,
            'storm_risk' => round($stormRisk, 2),
        ];
    }

    /**
     * Map WMO code to weather condition string.
     */
    protected function mapWeatherCode(int $code): string
    {
        switch ($code) {
            case 0:
                return 'Clear Sky';
            case 1:
            case 2:
            case 3:
                return 'Partly Cloudy';
            case 45:
            case 48:
                return 'Foggy';
            case 51:
            case 53:
            case 55:
            case 56:
            case 57:
                return 'Drizzle';
            case 61:
            case 63:
            case 65:
            case 66:
            case 67:
            case 80:
            case 81:
            case 82:
                return 'Heavy Rain';
            case 71:
            case 73:
            case 75:
            case 77:
            case 85:
            case 86:
                return 'Snowy';
            case 95:
            case 96:
            case 99:
                return 'Thunderstorm';
            default:
                return 'Overcast';
        }
    }
}
