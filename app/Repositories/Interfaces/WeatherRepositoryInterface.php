<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\WeatherCache;
use App\Models\WeatherHistory;
use Illuminate\Database\Eloquent\Collection;

interface WeatherRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get valid weather cache by coordinates.
     *
     * @param float $latitude
     * @param float $longitude
     * @return WeatherCache|null
     */
    public function getCache(float $latitude, float $longitude): ?WeatherCache;

    /**
     * Save/update weather cache.
     *
     * @param float $latitude
     * @param float $longitude
     * @param array<string, mixed> $data
     * @param int $ttlMinutes
     * @return WeatherCache
     */
    public function saveCache(float $latitude, float $longitude, array $data, int $ttlMinutes = 30): WeatherCache;

    /**
     * Save daily weather history record.
     *
     * @param int $countryId
     * @param float $temp
     * @param float $wind
     * @param int $humidity
     * @param string $recordedAt
     * @return WeatherHistory
     */
    public function saveHistory(int $countryId, float $temp, float $wind, int $humidity, string $recordedAt): WeatherCache;

    /**
     * Get weather history records.
     *
     * @param int $countryId
     * @param int $limit
     * @return Collection<int, WeatherHistory>
     */
    public function getHistory(int $countryId, int $limit = 30): Collection;
}
