<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Repositories\Interfaces\ApiLogRepositoryInterface;
use App\Repositories\Interfaces\WeatherRepositoryInterface;
use App\Services\Contracts\WeatherServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService implements WeatherServiceInterface
{
    /**
     * @var WeatherRepositoryInterface
     */
    protected WeatherRepositoryInterface $weatherRepo;

    /**
     * @var ApiLogRepositoryInterface
     */
    protected ApiLogRepositoryInterface $apiLogRepo;

    /**
     * WeatherService constructor.
     *
     * @param WeatherRepositoryInterface $weatherRepo
     * @param ApiLogRepositoryInterface $apiLogRepo
     */
    public function __construct(
        WeatherRepositoryInterface $weatherRepo,
        ApiLogRepositoryInterface $apiLogRepo
    ) {
        $this->weatherRepo = $weatherRepo;
        $this->apiLogRepo = $apiLogRepo;
    }

    /**
     * @inheritDoc
     */
    public function getWeatherData(float $latitude, float $longitude, bool $forceRefresh = false): array
    {
        if (!$forceRefresh) {
            $cached = $this->weatherRepo->getCache($latitude, $longitude);
            if ($cached) {
                return $cached->weather_data;
            }
        }

        $endpoint = 'https://api.open-meteo.com/v1/forecast';
        $params = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current' => 'temperature_2m,relative_humidity_2m,wind_speed_10m',
        ];

        $startTime = microtime(true);
        $statusCode = null;
        $isSuccess = false;
        $errorMessage = null;

        try {
            $response = Http::timeout(10)->get($endpoint, $params);
            $statusCode = $response->status();
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);

            if ($response->successful()) {
                $isSuccess = true;
                $data = $response->json();
                $this->weatherRepo->saveCache($latitude, $longitude, $data);

                $this->apiLogRepo->log('Open-Meteo API', $endpoint, 'GET', $statusCode, true, null, $durationMs);

                return $data;
            }

            $errorMessage = $response->body();
            $this->apiLogRepo->log('Open-Meteo API', $endpoint, 'GET', $statusCode, false, $errorMessage, $durationMs);
        } catch (\Exception $e) {
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);
            $errorMessage = $e->getMessage();
            $this->apiLogRepo->log('Open-Meteo API', $endpoint, 'GET', $statusCode, false, $errorMessage, $durationMs);
            Log::error('Open-Meteo API Error: ' . $errorMessage);
        }

        // Fallback response if API fails
        return [
            'current' => [
                'temperature_2m' => 0.0,
                'relative_humidity_2m' => 0,
                'wind_speed_10m' => 0.0,
            ],
            'fallback' => true,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getWeatherHistory(int $countryId, int $limit = 30): Collection
    {
        return $this->weatherRepo->getHistory($countryId, $limit);
    }
}
