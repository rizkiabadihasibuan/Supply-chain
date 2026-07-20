<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\WeatherCache;
use App\Models\WeatherHistory;
use App\Repositories\Interfaces\WeatherRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class WeatherRepository extends BaseRepository implements WeatherRepositoryInterface
{
    /**
     * WeatherRepository constructor.
     *
     * @param WeatherCache $model
     */
    public function __construct(WeatherCache $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getCache(float $latitude, float $longitude): ?WeatherCache
    {
        return WeatherCache::valid()
            ->where('latitude', $latitude)
            ->where('longitude', $longitude)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function saveCache(float $latitude, float $longitude, array $data, int $ttlMinutes = 30): WeatherCache
    {
        return WeatherCache::updateOrCreate(
            [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ],
            [
                'weather_data' => $data,
                'expires_at' => Carbon::now()->addMinutes($ttlMinutes),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function saveHistory(int $countryId, float $temp, float $wind, int $humidity, string $recordedAt): WeatherCache
    {
        return WeatherCache::updateOrCreate(
            [
                'latitude' => 0.0,
                'longitude' => 0.0,
            ],
            [
                'weather_data' => ['temp' => $temp, 'wind' => $wind, 'humidity' => $humidity],
                'expires_at' => Carbon::now()->addMinutes(30),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getHistory(int $countryId, int $limit = 30): Collection
    {
        return WeatherCache::latest()
            ->limit($limit)
            ->get();
    }
}
