<?php

declare(strict_types=1);

namespace App\DTO\Mappers;

use App\DTO\WeatherDTO;
use App\Models\WeatherHistory;

class WeatherMapper
{
    /**
     * Convert WeatherHistory model to WeatherDTO.
     *
     * @param WeatherHistory $model
     * @return WeatherDTO
     */
    public static function toDTO(WeatherHistory $model): WeatherDTO
    {
        return WeatherDTO::fromArray([
            'latitude' => $model->country?->coordinates?->latitude ?? 0.0,
            'longitude' => $model->country?->coordinates?->longitude ?? 0.0,
            'temperature' => $model->temperature,
            'wind_speed' => $model->wind_speed,
            'humidity' => $model->humidity,
            'recorded_at' => $model->recorded_at?->toDateTimeString(),
        ]);
    }
}
