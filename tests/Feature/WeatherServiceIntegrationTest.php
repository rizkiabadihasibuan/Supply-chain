<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use App\Services\WeatherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherServiceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $country;

    protected $mockWeatherData;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);

        $analystRole = Role::where('name', 'Analyst')->first();
        $this->user = User::factory()->create([
            'role_id' => $analystRole->id,
        ]);

        // Create country
        $this->country = Country::create([
            'code' => 'DE',
            'name' => 'Germany',
            'iso2' => 'DE',
            'iso3' => 'DEU',
            'latitude' => 52.52,
            'longitude' => 13.40,
        ]);

        // Mock Open-Meteo API response data
        $this->mockWeatherData = [
            'current' => [
                'temperature_2m' => 22.5,
                'relative_humidity_2m' => 65,
                'precipitation' => 0.5,
                'rain' => 0.5,
                'weather_code' => 3,
                'wind_speed_10m' => 12.0,
                'wind_gusts_10m' => 18.0,
            ],
            'daily' => [
                'time' => ['2026-07-14', '2026-07-15'],
                'weather_code' => [3, 0],
                'temperature_2m_max' => [25.0, 27.0],
                'temperature_2m_min' => [15.0, 16.0],
                'precipitation_sum' => [1.0, 0.0],
            ],
        ];
    }

    /**
     * Test WeatherService can sync weather indicators into database.
     */
    public function test_service_can_sync_weather_data_into_local_database(): void
    {
        Http::fake([
            'https://api.open-meteo.com/v1/forecast*' => Http::response($this->mockWeatherData, 200),
        ]);

        $service = app(WeatherService::class);
        $country = $service->syncWeather('DE', true);

        $this->assertNotNull($country);
        $this->assertEquals(22.5, $country->current_weather_temp);
        $this->assertEquals('Partly Cloudy', $country->current_weather_condition);
        $this->assertEquals(12.0, $country->current_weather_wind_speed);
        $this->assertEquals(0.5, $country->current_weather_precipitation);
        $this->assertEquals(65, $country->current_weather_humidity);
        $this->assertEquals(0.5, $country->current_weather_rain);
        $this->assertEquals(3, $country->current_weather_code);
        $this->assertIsArray($country->weather_forecast_7_days);
        $this->assertCount(2, $country->weather_forecast_7_days);

        // Verify audit log exists
        $this->assertDatabaseHas('activity_logs', [
            'log_type' => 'audit',
            'description' => "Berhasil menyelaraskan data cuaca negara 'Germany' dari Open Meteo API.",
        ]);
    }

    /**
     * Test caching behavior: second call should not trigger HTTP requests.
     */
    public function test_service_caches_weather_responses(): void
    {
        Http::fake([
            'https://api.open-meteo.com/v1/forecast*' => Http::response($this->mockWeatherData, 200),
        ]);

        $service = app(WeatherService::class);
        Cache::forget('weather_service_DE');

        // First call
        $service->syncWeather('DE', false);
        Http::assertSentCount(1);

        // Second call (hits cache)
        $service->syncWeather('DE', false);
        Http::assertSentCount(1); // should still be 1
    }

    /**
     * Test exception is thrown when Open Meteo API returns empty or invalid.
     */
    public function test_service_throws_exception_on_invalid_or_null_weather(): void
    {
        Http::fake([
            'https://api.open-meteo.com/v1/forecast*' => Http::response([], 200),
        ]);

        $service = app(WeatherService::class);

        $this->expectException(\RuntimeException::class);
        $service->syncWeather('DE', true);
    }

    /**
     * Test artisan command syncs data.
     */
    public function test_artisan_command_syncs_weather_data(): void
    {
        Http::fake([
            'https://api.open-meteo.com/v1/forecast*' => Http::response($this->mockWeatherData, 200),
        ]);

        $exitCode = Artisan::call('weather:sync', [
            'country' => 'DE',
            '--force' => true,
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertDatabaseHas('countries', [
            'code' => 'DE',
            'current_weather_temp' => 22.5,
        ]);
    }

    /**
     * Test controller single and mass weather sync endpoints.
     */
    public function test_controller_sync_endpoints(): void
    {
        Http::fake([
            'https://api.open-meteo.com/v1/forecast*' => Http::response($this->mockWeatherData, 200),
        ]);

        // Single sync endpoint
        $response = $this->actingAs($this->user)
            ->postJson(route('countries.sync-weather', ['code' => 'DE']));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => "Data cuaca negara 'Germany' berhasil diperbarui dari Open Meteo API.",
            ]);

        // Mass sync endpoint
        $responseAll = $this->actingAs($this->user)
            ->postJson(route('countries.sync-all-weather'));

        $responseAll->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Sinkronisasi cuaca seluruh negara selesai. Sukses: 1, Gagal: 0.',
            ]);
    }
}
