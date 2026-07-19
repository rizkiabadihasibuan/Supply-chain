<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\WeatherCache;
use App\Models\WeatherHistory;
use Illuminate\Database\Eloquent\Collection;

interface WeatherServiceInterface
{
    /**
     * Fetch weather data for coordinates (checks cache, else fetches from Open-Meteo).
     *
     * @param float $latitude
     * @param float $longitude
     * @param bool $forceRefresh
     * @return array<string, mixed>
     */
    public function getWeatherData(float $latitude, float $longitude, bool $forceRefresh = false): array;

    /**
     * Get weather history records.
     *
     * @param int $countryId
     * @param int $limit
     * @return Collection<int, WeatherHistory>
     */
    public function getWeatherHistory(int $countryId, int $limit = 30): Collection;
}
