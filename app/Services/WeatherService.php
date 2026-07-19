<?php

namespace App\Services;

use App\Integrations\OpenMeteo\WeatherApiClient;
use App\Integrations\OpenMeteo\WeatherMapper;
use App\Integrations\OpenMeteo\WeatherCacheManager;
use App\Integrations\OpenMeteo\WeatherDTO;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected $apiClient;
    protected $mapper;
    protected $cacheManager;
    protected $countryService;

    public function __construct(
        WeatherApiClient $apiClient,
        WeatherMapper $mapper,
        WeatherCacheManager $cacheManager,
        CountryService $countryService
    ) {
        $this->apiClient = $apiClient;
        $this->mapper = $mapper;
        $this->cacheManager = $cacheManager;
        $this->countryService = $countryService;
    }

    /**
     * Get current weather by country name
     * Automatically resolves country name to coordinates using CountryService
     */
    public function getCurrentWeather(string $country, bool $forceRefresh = false): ?array
    {
        $startTime = microtime(true);
        
        try {
            // Get country data and coordinates from CountryService
            $countryData = $this->countryService->getCountryByName($country);
            
            if (!$countryData) {
                Log::warning("WeatherService: Country not found", ['country' => $country]);
                return null;
            }

            $latitude = $countryData['latitude'];
            $longitude = $countryData['longitude'];
            $timezone = $countryData['timezone'] ?? 'UTC';
            $countryName = $countryData['country'];

            // Get weather data by coordinates
            $dto = $this->getWeather($latitude, $longitude, $timezone, $forceRefresh);

            $duration = (microtime(true) - $startTime) * 1000;
            Log::info("WeatherService: getCurrentWeather success", [
                'country' => $countryName,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'duration_ms' => round($duration, 2),
                'status' => 'success',
            ]);

            return $this->formatWeatherResponse($dto, $countryName);
        } catch (\Throwable $e) {
            $duration = (microtime(true) - $startTime) * 1000;
            Log::error("WeatherService: getCurrentWeather failed", [
                'country' => $country,
                'duration_ms' => round($duration, 2),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get weather by coordinates (latitude, longitude)
     */
    public function getWeatherByCoordinate(float $latitude, float $longitude, string $timezone = 'UTC', bool $forceRefresh = false): ?array
    {
        $startTime = microtime(true);
        
        try {
            $dto = $this->getWeather($latitude, $longitude, $timezone, $forceRefresh);

            $duration = (microtime(true) - $startTime) * 1000;
            Log::info("WeatherService: getWeatherByCoordinate success", [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'duration_ms' => round($duration, 2),
                'status' => 'success',
            ]);

            return $this->formatWeatherResponse($dto, "Coordinates ({$latitude}, {$longitude})");
        } catch (\Throwable $e) {
            $duration = (microtime(true) - $startTime) * 1000;
            Log::error("WeatherService: getWeatherByCoordinate failed", [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'duration_ms' => round($duration, 2),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get weather summary by country name
     */
    public function getWeatherSummary(string $country, bool $forceRefresh = false): ?array
    {
        return $this->getCurrentWeather($country, $forceRefresh);
    }

    /**
     * Map weather code to description
     * Based on official Open-Meteo WMO Weather interpretation codes
     */
    public function mapWeatherCode(int $code): string
    {
        return match ($code) {
            0 => 'Clear Sky',
            1 => 'Mainly Clear',
            2 => 'Partly Cloudy',
            3 => 'Overcast',
            45 => 'Foggy',
            48 => 'Depositing Rime Fog',
            51 => 'Light Drizzle',
            53 => 'Moderate Drizzle',
            55 => 'Dense Drizzle',
            61 => 'Slight Rain',
            63 => 'Moderate Rain',
            65 => 'Heavy Rain',
            71 => 'Slight Snow',
            73 => 'Moderate Snow',
            75 => 'Heavy Snow',
            77 => 'Snow Grains',
            80 => 'Slight Rain Showers',
            81 => 'Moderate Rain Showers',
            82 => 'Violent Rain Showers',
            85 => 'Slight Snow Showers',
            86 => 'Heavy Snow Showers',
            95 => 'Thunderstorm',
            96 => 'Thunderstorm with Slight Hail',
            99 => 'Thunderstorm with Heavy Hail',
            default => 'Unknown',
        };
    }

    /**
     * Get Weather Data and Map to DTO (Internal method)
     */
    protected function getWeather(float $latitude, float $longitude, string $timezone = 'UTC', bool $forceRefresh = false): WeatherDTO
    {
        $cacheKey = "weather_forecast_{$latitude}_{$longitude}";
        $backupKey = "weather_forecast_backup_{$latitude}_{$longitude}";
        $ttl = (int) Config::get('weather.cache_ttl', 600); // 10 minutes default

        $rawData = null;

        if (!$forceRefresh && Cache::has($cacheKey)) {
            $rawData = Cache::get($cacheKey);
            Log::info("WeatherService: Cache HIT for coordinates [{$latitude}, {$longitude}]");
        } else {
            Log::info("WeatherService: Cache MISS. Fetching fresh forecast for [{$latitude}, {$longitude}]");
            try {
                $rawData = $this->apiClient->getForecast($latitude, $longitude, $timezone);
                
                // Save to active cache and backup cache
                Cache::put($cacheKey, $rawData, now()->addSeconds($ttl));
                Cache::forever($backupKey, $rawData);
            } catch (\Throwable $e) {
                Log::warning("WeatherService: API call failed: {$e->getMessage()}. Attempting fallback to backup cache...");
                
                if (Cache::has($backupKey)) {
                    $rawData = Cache::get($backupKey);
                    Log::info("WeatherService: Restored forecast from backup cache.");
                } else {
                    Log::error("WeatherService: No backup cache found for [{$latitude}, {$longitude}]. Propagating API error.");
                    throw $e;
                }
            }
        }

        return $this->mapper->map($rawData);
    }

    /**
     * Format WeatherDTO to response array
     */
    protected function formatWeatherResponse(WeatherDTO $dto, string $countryName): array
    {
        return [
            'country' => $countryName,
            'temperature' => (float) $dto->temperature,
            'rain' => (float) $dto->rain,
            'wind_speed' => (float) $dto->windSpeed,
            'weather_code' => (int) $dto->weatherCode,
            'weather_description' => $dto->weatherDescription,
            'latitude' => (float) $dto->latitude,
            'longitude' => (float) $dto->longitude,
            'timezone' => $dto->timezone,
            'current_time' => $dto->timestamp,
            'updated_at' => now()->toIso8601String(),
        ];
    }
}