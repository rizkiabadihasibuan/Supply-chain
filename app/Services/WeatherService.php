<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Country;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    /**
     * Cache duration (1 hour).
     */
    protected const CACHE_DURATION = 3600;

    /**
     * Fetch weather data for a country (current & 7-day forecast) and save to database.
     *
     * @throws \RuntimeException
     */
    public function syncWeather(string $code, bool $forceRefresh = false): Country
    {
        $code = strtoupper(trim($code));

        $country = Country::where('code', $code)
            ->orWhere('iso2', $code)
            ->orWhere('iso3', $code)
            ->first();

        if (! $country) {
            throw new \RuntimeException("Negara dengan kode '{$code}' tidak ditemukan di database lokal.");
        }

        if ($country->latitude === null || $country->longitude === null) {
            throw new \RuntimeException("Negara '{$country->name}' tidak memiliki data koordinat (latitude/longitude).");
        }

        $lat = (float) $country->latitude;
        $lng = (float) $country->longitude;
        $cacheKey = "weather_service_{$code}";

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        if (! $forceRefresh && Cache::has($cacheKey)) {
            return $country;
        }

        $rawData = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($lat, $lng) {
            $endpoint = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lng}&current=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,rain,weather_code,wind_speed_10m,wind_direction_10m,wind_gusts_10m&daily=weather_code,temperature_2m_max,temperature_2m_min,precipitation_sum&timezone=auto";
            $startTime = microtime(true);
            $responseStatus = null;

            try {
                Log::debug("Memanggil Open Meteo API untuk koordinat: [{$lat}, {$lng}]");

                $response = Http::withoutVerifying()->timeout(10)->retry(3, 200)->get($endpoint);
                $responseStatus = $response->status();
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);

                $this->logApiCall($endpoint, $responseStatus, $executionTime);

                if ($response->successful()) {
                    return $response->json();
                }

                throw new \RuntimeException("Open-Meteo API returned status code: {$responseStatus}");
            } catch (\Exception $e) {
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);
                $this->logApiCall($endpoint, 500, $executionTime);
                throw $e;
            }
        });

        if (empty($rawData['current'])) {
            throw new \RuntimeException('Format respons dari Open Meteo API tidak valid.');
        }

        $parsedCurrent = $this->parseCurrent($rawData['current']);
        $parsedForecast = ! empty($rawData['daily']) ? $this->parseForecast($rawData['daily']) : null;

        // Save to database
        return DB::transaction(function () use ($country, $parsedCurrent, $parsedForecast) {
            $country->current_weather_temp = $parsedCurrent['temp'];
            $country->current_weather_condition = $parsedCurrent['condition'];
            $country->current_weather_wind_speed = $parsedCurrent['wind_speed'];
            $country->current_weather_precipitation = $parsedCurrent['precipitation'];
            $country->current_weather_storm_risk = $parsedCurrent['storm_risk'];

            // New detailed columns
            $country->current_weather_humidity = $parsedCurrent['humidity'];
            $country->current_weather_wind_direction = $parsedCurrent['wind_direction'];
            $country->current_weather_rain = $parsedCurrent['rain'];
            $country->current_weather_code = $parsedCurrent['weather_code'];

            if ($parsedForecast !== null) {
                $country->weather_forecast_7_days = $parsedForecast;
            }

            $country->save();

            // Log activity log
            $this->logAudit("Berhasil menyelaraskan data cuaca negara '{$country->name}' dari Open Meteo API.", [
                'country_id' => $country->id,
                'country_code' => $country->code,
                'temp' => $parsedCurrent['temp'],
                'condition' => $parsedCurrent['condition'],
            ]);

            Log::info("Data cuaca negara '{$country->name}' ({$country->code}) berhasil diperbarui.");

            return $country;
        });
    }

    /**
     * Synchronize weather for all countries in local database.
     */
    public function syncAllWeather(bool $forceRefresh = false): array
    {
        $countries = Country::all();
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($countries as $country) {
            if ($country->latitude === null || $country->longitude === null) {
                $results['failed'][] = [
                    'code' => $country->code,
                    'error' => 'Negara tidak memiliki koordinat latitude/longitude.',
                ];

                continue;
            }

            try {
                $this->syncWeather($country->code, $forceRefresh);
                $results['success'][] = $country->code;
            } catch (\Exception $e) {
                Log::error("Gagal menyinkronkan cuaca '{$country->code}' selama syncAllWeather: ".$e->getMessage());
                $results['failed'][] = [
                    'code' => $country->code,
                    'error' => $e->getMessage(),
                ];
            }
        }

        Log::info('Sinkronisasi cuaca seluruh negara selesai. Sukses: '.count($results['success']).', Gagal: '.count($results['failed']));

        return $results;
    }

    /**
     * Parse current weather response.
     */
    protected function parseCurrent(array $current): array
    {
        $temp = $current['temperature_2m'] ?? 0.0;
        $humidity = $current['relative_humidity_2m'] ?? null;
        $precipitation = $current['precipitation'] ?? 0.0;
        $rain = $current['rain'] ?? 0.0;
        $windSpeed = $current['wind_speed_10m'] ?? 0.0;
        $windDirection = $current['wind_direction_10m'] ?? null;
        $windGusts = $current['wind_gusts_10m'] ?? 0.0;
        $weatherCode = $current['weather_code'] ?? 0;

        $condition = $this->mapWeatherCode($weatherCode);

        // Calculate storm risk (0% - 100%)
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
        $stormRisk = min(100.0, max(0.0, $stormRisk));

        return [
            'temp' => $temp,
            'humidity' => $humidity,
            'precipitation' => $precipitation,
            'rain' => $rain,
            'wind_speed' => $windSpeed,
            'wind_direction' => $windDirection,
            'weather_code' => $weatherCode,
            'condition' => $condition,
            'storm_risk' => round($stormRisk, 2),
        ];
    }

    /**
     * Parse 7-day daily forecast response.
     */
    protected function parseForecast(array $daily): array
    {
        $forecast = [];
        $timeArray = $daily['time'] ?? [];

        foreach ($timeArray as $i => $date) {
            $code = $daily['weather_code'][$i] ?? 0;
            $forecast[] = [
                'date' => $date,
                'temp_max' => $daily['temperature_2m_max'][$i] ?? null,
                'temp_min' => $daily['temperature_2m_min'][$i] ?? null,
                'precipitation' => $daily['precipitation_sum'][$i] ?? null,
                'weather_code' => $code,
                'condition' => $this->mapWeatherCode($code),
            ];
        }

        return $forecast;
    }

    /**
     * Map weather code to string.
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

    /**
     * Log API call to activity logs.
     */
    protected function logApiCall(string $endpoint, int $status, float $executionTime): void
    {
        try {
            ActivityLog::create([
                'log_type' => 'api_request',
                'description' => 'Panggilan Open-Meteo API untuk cuaca koordinat',
                'metadata' => [
                    'api_name' => 'Open-Meteo API (WeatherService)',
                    'endpoint' => $endpoint,
                    'response_status' => $status,
                    'execution_time' => $executionTime,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mencatat log API Open Meteo: '.$e->getMessage());
        }
    }

    /**
     * Log audit trail.
     */
    protected function logAudit(string $description, ?array $metadata = null): void
    {
        try {
            ActivityLog::create([
                'log_type' => 'audit',
                'description' => $description,
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mencatat log audit cuaca ke database: '.$e->getMessage());
        }
    }
}
