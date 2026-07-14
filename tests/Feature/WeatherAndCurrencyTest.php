<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Country;
use App\Services\OpenMeteoService;
use App\Services\ExchangeRateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherAndCurrencyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear cache
        Cache::forget('open_meteo_ID');
        Cache::forget('exchange_rates_usd_base');
    }

    /**
     * Test OpenMeteoService can fetch and parse weather data.
     */
    public function test_open_meteo_service_fetches_and_parses_weather(): void
    {
        Http::fake([
            'https://api.open-meteo.com/v1/forecast*' => Http::response([
                'current' => [
                    'temperature_2m' => 31.5,
                    'precipitation' => 4.5,
                    'weather_code' => 95, // Thunderstorm
                    'wind_speed_10m' => 20.5,
                    'wind_gusts_10m' => 45.0,
                ]
            ], 200)
        ]);

        $service = app(OpenMeteoService::class);
        $result = $service->fetchWeather('ID', -6.2147, 106.8451);

        $this->assertNotNull($result);
        $this->assertEquals(31.5, $result['temp']);
        $this->assertEquals(4.5, $result['precipitation']);
        $this->assertEquals('Thunderstorm', $result['condition']);
        $this->assertEquals(20.5, $result['wind_speed']);
        
        // Storm risk verification based on our formula:
        // wind_speed > 15 -> (20.5 - 15) * 1.5 = 8.25
        // wind_gusts > 30 -> (45.0 - 30) * 2.0 = 30.0
        // precipitation > 2 -> 4.5 * 5.0 = 22.5
        // Total = 8.25 + 30.0 + 22.5 = 60.75
        $this->assertEquals(60.75, $result['storm_risk']);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'log_type' => 'api_request',
            'description' => 'Panggilan Open-Meteo API untuk cuaca koordinat',
        ]);
    }

    /**
     * Test ExchangeRateService can fetch exchange rates.
     */
    public function test_exchange_rate_service_fetches_rates(): void
    {
        Http::fake([
            'https://open.er-api.com/v6/latest/USD' => Http::response([
                'rates' => [
                    'EUR' => 0.92,
                    'IDR' => 16000.00,
                    'AUD' => 1.50,
                ]
            ], 200)
        ]);

        $service = app(ExchangeRateService::class);
        
        // Fetch all rates
        $rates = $service->fetchRates();
        $this->assertNotNull($rates);
        $this->assertEquals(16000.00, $rates['IDR']);

        // Test specific currency rate
        $idrRate = $service->getRateAgainstUsd('IDR');
        $this->assertEquals(16000.00, $idrRate);

        $eurRate = $service->getRateAgainstUsd('EUR');
        $this->assertEquals(0.92, $eurRate);

        $usdRate = $service->getRateAgainstUsd('USD');
        $this->assertEquals(1.0, $usdRate);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'log_type' => 'api_request',
            'description' => 'Panggilan ExchangeRate API untuk kurs mata uang',
        ]);
    }
}
