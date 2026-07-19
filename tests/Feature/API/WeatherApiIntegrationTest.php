<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeatherApiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected WeatherService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->service = app(WeatherService::class);
        Cache::flush();
    }

    /** @test */
    public function test_weather_service_caches_and_uses_backup_on_failure(): void
    {
        // 1. Mock sequence: 1st request succeeds, subsequent requests fail
        Http::fake([
            'api.open-meteo.com/*' => Http::sequence()
                ->push([
                    'latitude' => -6.20,
                    'longitude' => 106.81,
                    'timezone' => 'Asia/Jakarta',
                    'current' => [
                        'temperature_2m' => 31.2,
                        'weather_code' => 1,
                        'wind_speed_10m' => 8.5,
                        'rain' => 1.2,
                        'time' => '2026-07-19T20:30',
                    ],
                    'hourly' => [
                        'precipitation_probability' => [0],
                    ],
                ], 200)
                ->pushStatus(500)
                ->pushStatus(500)
        ]);

        // 1st Call - Cache MISS, API success
        $weatherData = $this->service->getWeatherByCoordinate(-6.20, 106.81);
        $this->assertEquals(31.2, $weatherData['temperature']);
        $this->assertEquals(1.2, $weatherData['rain']);
        $this->assertEquals('Mainly Clear', $weatherData['weather_description']);

        // Verify standard cache exists
        $this->assertTrue(Cache::has('weather_forecast_-6.2_106.81'));
        $this->assertTrue(Cache::has('weather_forecast_backup_-6.2_106.81'));

        // Clear standard cache to force next API call
        Cache::forget('weather_forecast_-6.2_106.81');

        // 2nd Call - Cache MISS, API fails (500), recovers from backup cache
        $weatherData2 = $this->service->getWeatherByCoordinate(-6.20, 106.81);
        $this->assertEquals(31.2, $weatherData2['temperature']); // values recovered from backup cache

        // Clear backup cache to verify exception on next failure
        Cache::forget('weather_forecast_backup_-6.2_106.81');

        // 3rd Call - Cache MISS, API fails, no backup cache -> throws exception
        $this->expectException(\Throwable::class);
        $this->service->getWeatherByCoordinate(-6.20, 106.81);
    }
}
