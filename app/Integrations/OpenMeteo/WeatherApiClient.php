<?php

namespace App\Integrations\OpenMeteo;

use App\Integrations\Clients\BaseApiClient;
use Illuminate\Support\Facades\Config;

class WeatherApiClient extends BaseApiClient
{
    protected function getBaseUrl(): string
    {
        return Config::get('api.integrations.open_meteo.base_url', 'https://api.open-meteo.com/v1');
    }

    /**
     * Fetch forecast from Open-Meteo
     */
    public function getForecast(float $latitude, float $longitude, string $timezone = 'UTC'): array
    {
        $query = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current' => 'temperature_2m,relative_humidity_2m,weather_code,surface_pressure,wind_speed_10m,rain',
            'hourly' => 'temperature_2m,precipitation_probability,weather_code',
            'daily' => 'weather_code,temperature_2m_max,temperature_2m_min',
            'timezone' => $timezone,
        ];

        return $this->get('/forecast', $query);
    }
}