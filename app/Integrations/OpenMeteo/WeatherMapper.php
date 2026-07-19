<?php

namespace App\Integrations\OpenMeteo;

class WeatherMapper
{
    /**
     * Map Open-Meteo JSON Response to Internal WeatherDTO
     * Based on official WMO Weather interpretation codes
     */
    public function map(array $response): WeatherDTO
    {
        $current = $response['current_weather'] ?? $response['current'] ?? [];
        $code = (int) ($current['weathercode'] ?? $current['weather_code'] ?? 0);

        $description = $this->mapWeatherCode($code);
        
        return new WeatherDTO([
            'latitude' => (float) ($response['latitude'] ?? 0.0),
            'longitude' => (float) ($response['longitude'] ?? 0.0),
            'timezone' => $response['timezone'] ?? 'UTC',
            'temperature' => (float) ($current['temperature_2m'] ?? $current['temperature'] ?? 0.0),
            'weatherCode' => $code,
            'weatherDescription' => $description,
            'rain' => (float) ($current['rain'] ?? 0.0),
            'windSpeed' => (float) ($current['wind_speed_10m'] ?? $current['windspeed'] ?? $current['wind_speed'] ?? 0.0),
            'humidity' => (float) ($current['relative_humidity_2m'] ?? 0.0),
            'pressure' => (float) ($current['surface_pressure'] ?? 0.0),
            'rainProbability' => (int) ($response['hourly']['precipitation_probability'][0] ?? 0),
            'hourlyForecast' => $response['hourly'] ?? [],
            'dailyForecast' => $response['daily'] ?? [],
            'timestamp' => $current['time'] ?? now()->toIso8601String(),
        ]);
    }

    /**
     * Map WMO Weather Code to description
     * Based on official Open-Meteo WMO codes
     */
    private function mapWeatherCode(int $code): string
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
}