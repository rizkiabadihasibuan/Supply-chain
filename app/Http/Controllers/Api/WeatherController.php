<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\WeatherService;
use App\Http\Resources\Weather\WeatherResource;
use App\Http\Resources\Weather\WeatherCollection;
use App\Http\Requests\Weather\WeatherFilterRequest;
use App\Http\Requests\Weather\WeatherRefreshRequest;

class WeatherController extends BaseApiController
{
    /**
     * @var WeatherService
     */
    protected $WeatherService;

    /**
     * Constructor for Dependency Injection
     *
     * @param WeatherService $WeatherService
     */
    public function __construct(WeatherService $WeatherService)
    {
        $this->WeatherService = $WeatherService;
    }

    /**
     * index method - Get weather by country or coordinates
     * GET /api/weather?country=Indonesia
     * GET /api/weather?latitude=-6.2&longitude=106.81
     */
    public function index(Request $request)
    {
        $startTime = microtime(true);
        try {
            $countryParam = $request->query('country');
            $latitude = $request->query('latitude');
            $longitude = $request->query('longitude');

            $weatherData = null;

            // 1. Get by country name
            if ($countryParam) {
                $weatherData = $this->WeatherService->getCurrentWeather($countryParam);
                
                if (!$weatherData) {
                    return $this->sendError('Country not found or weather data unavailable', [], 404);
                }
            }
            // 2. Get by coordinates
            elseif ($latitude !== null && $longitude !== null) {
                $weatherData = $this->WeatherService->getWeatherByCoordinate(
                    (float) $latitude,
                    (float) $longitude
                );
                
                if (!$weatherData) {
                    return $this->sendError('Weather data unavailable for provided coordinates', [], 400);
                }
            }
            // 3. Fallback to default country (Indonesia)
            else {
                $weatherData = $this->WeatherService->getCurrentWeather('Indonesia');
            }

            // 4. Log success
            $duration = (microtime(true) - $startTime) * 1000;
            \Illuminate\Support\Facades\Log::info("Weather index request success", [
                'country' => $countryParam ?? 'N/A',
                'latitude' => $latitude ?? 'N/A',
                'longitude' => $longitude ?? 'N/A',
                'duration_ms' => round($duration, 2),
                'status' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Weather data retrieved successfully',
                'data' => $weatherData
            ], 200);

        } catch (\App\Integrations\Exceptions\ApiException $e) {
            $duration = (microtime(true) - $startTime) * 1000;
            \Illuminate\Support\Facades\Log::error("Weather index API error", [
                'duration_ms' => round($duration, 2),
                'status' => 'error',
                'error_code' => $e->getCode(),
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Weather API Error',
                'errors' => [$e->getMessage()],
            ], $e->getCode() ?: 500);
        } catch (\Throwable $e) {
            $duration = (microtime(true) - $startTime) * 1000;
            \Illuminate\Support\Facades\Log::error("Weather index unexpected error", [
                'duration_ms' => round($duration, 2),
                'status' => 'error',
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unexpected error occurred',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }

    /**
     * show method - Get weather summary by country
     * GET /api/weather/{country}
     */
    public function show(string $country)
    {
        $startTime = microtime(true);
        try {
            $weatherData = $this->WeatherService->getWeatherSummary($country);

            if (!$weatherData) {
                return $this->sendError('Country not found or weather data unavailable', [], 404);
            }

            $duration = (microtime(true) - $startTime) * 1000;
            \Illuminate\Support\Facades\Log::info("Weather show request success", [
                'country' => $country,
                'duration_ms' => round($duration, 2),
                'status' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Weather summary retrieved successfully',
                'data' => $weatherData
            ], 200);

        } catch (\App\Integrations\Exceptions\ApiException $e) {
            $duration = (microtime(true) - $startTime) * 1000;
            \Illuminate\Support\Facades\Log::error("Weather show API error", [
                'country' => $country,
                'duration_ms' => round($duration, 2),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Weather API Error',
                'errors' => [$e->getMessage()],
            ], $e->getCode() ?: 500);
        } catch (\Throwable $e) {
            $duration = (microtime(true) - $startTime) * 1000;
            \Illuminate\Support\Facades\Log::error("Weather show unexpected error", [
                'country' => $country,
                'duration_ms' => round($duration, 2),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unexpected error occurred',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }
}