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
     * @param WeatherHistory $model
     */
    public function __construct(WeatherHistory $model)
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
    public function saveCache(float $latitude, float $longitude, array $data, int $ttlHours = 3): WeatherCache
    {
        return WeatherCache::updateOrCreate(
            [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ],
            [
                'weather_data' => $data,
                'expires_at' => Carbon::now()->addHours($ttlHours),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function saveHistory(int $countryId, float $temp, float $wind, int $humidity, string $recordedAt): WeatherHistory
    {
        return WeatherHistory::updateOrCreate(
            [
                'country_id' => $countryId,
                'recorded_at' => Carbon::parse($recordedAt),
            ],
            [
                'temperature' => $temp,
                'wind_speed' => $wind,
                'humidity' => $humidity,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getHistory(int $countryId, int $limit = 30): Collection
    {
        return $this->model->byCountry($countryId)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
