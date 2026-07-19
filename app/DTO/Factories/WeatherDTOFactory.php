<?php

declare(strict_types=1);

namespace App\DTO\Factories;

use App\DTO\WeatherDTO;

class WeatherDTOFactory
{
    /**
     * Create WeatherDTO instance from raw API payload.
     *
     * @param array<string, mixed> $payload
     * @param float $lat
     * @param float $lon
     * @return WeatherDTO
     */
    public static function createFromApi(array $payload, float $lat, float $lon): WeatherDTO
    {
        return WeatherDTO::fromArray([
            'latitude' => $lat,
            'longitude' => $lon,
            'temperature' => $payload['current']['temperature_2m'] ?? 0.0,
            'wind_speed' => $payload['current']['wind_speed_10m'] ?? 0.0,
            'humidity' => $payload['current']['relative_humidity_2m'] ?? 0,
            'recorded_at' => $payload['current']['time'] ?? null,
        ]);
    }
}
