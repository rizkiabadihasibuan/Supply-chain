<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use App\Services\CountryService;
use App\Services\WeatherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherControllerTest extends TestCase
{
    use RefreshDatabase;

    protected WeatherService $weatherService;
    protected CountryService $countryService;

    protected function setUp(): void
    {
        parent::setUp();

        // Boot services
        $this->countryService = app(CountryService::class);
        $this->weatherService = app(WeatherService::class);
    }

    /**
     * Test GET /api/weather?country=Indonesia returns valid data
     */
    public function test_get_weather_by_country_name(): void
    {
        // Create authenticated user
        $user = \App\Models\User::factory()->create();

        // Mock REST Countries API
        Http::fake([
            'restcountries.com/*' => Http::response([
                ['name' => ['common' => 'Indonesia'], 'latlng' => [-6.2, 106.81], 'cca2' => 'ID'],
            ], 200),
            'api.open-meteo.com/*' => Http::response([
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
            ], 200),
        ]);

        // 1. Make request
        $response = $this->actingAs($user)
            ->getJson('/api/weather?country=Indonesia');

        // 2. Assert response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'country',
                    'temperature',
                    'rain',
                    'wind_speed',
                    'weather_code',
                    'weather_description',
                    'latitude',
                    'longitude',
                    'timezone',
                    'current_time',
                    'updated_at',
                ]
            ])
            ->assertJsonPath('data.country', 'Indonesia')
            ->assertJsonPath('data.temperature', 31.2)
            ->assertJsonPath('data.weather_description', 'Mainly Clear');
    }

    /**
     * Test GET /api/weather?country=Invalid returns 404
     */
    public function test_get_weather_by_invalid_country_returns_404(): void
    {
        $user = \App\Models\User::factory()->create();

        // Mock REST Countries API with empty response
        Http::fake([
            'restcountries.com/*' => Http::response([], 200),
            'api.open-meteo.com/*' => Http::response([], 200),
        ]);

        // 1. Make request with invalid country
        $response = $this->actingAs($user)
            ->getJson('/api/weather?country=InvalidCountryXYZ');

        // 2. Assert 404 response
        $response->assertStatus(404)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Country not found or weather data unavailable');
    }

    /**
     * Test GET /api/weather with both country and coordinates returns weather data
     */
    public function test_get_weather_by_coordinates(): void
    {
        $user = \App\Models\User::factory()->create();

        // Mock Open-Meteo API directly (no country needed)
        Http::fake([
            'api.open-meteo.com/*' => Http::response([
                'latitude' => -6.20,
                'longitude' => 106.81,
                'timezone' => 'Asia/Jakarta',
                'current' => [
                    'temperature_2m' => 28.5,
                    'weather_code' => 0,
                    'wind_speed_10m' => 6.2,
                    'rain' => 0.0,
                    'time' => '2026-07-19T10:00',
                ],
                'hourly' => [
                    'precipitation_probability' => [0],
                ],
            ], 200),
        ]);

        // 1. Make request with coordinates
        $response = $this->actingAs($user)
            ->getJson('/api/weather?latitude=-6.2&longitude=106.81');

        // 2. Assert response
        $response->assertStatus(200)
            ->assertJsonPath('data.temperature', 28.5)
            ->assertJsonPath('data.weather_description', 'Clear Sky')
            ->assertJsonPath('data.latitude', -6.2)
            ->assertJsonPath('data.longitude', 106.81);
    }

    /**
     * Test GET /api/weather without parameters returns 400
     */
    public function test_get_weather_without_parameters_returns_400(): void
    {
        $user = \App\Models\User::factory()->create();

        // 1. Make request without params
        $response = $this->actingAs($user)
            ->getJson('/api/weather');

        // 2. Assert 400 response
        $response->assertStatus(400)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Parameters missing. Provide either country or (latitude & longitude).');
    }

    /**
     * Test GET /api/weather/{country} returns summary
     */
    public function test_get_weather_summary_by_country(): void
    {
        $user = \App\Models\User::factory()->create();

        // Mock APIs
        Http::fake([
            'restcountries.com/*' => Http::response([
                ['name' => ['common' => 'Indonesia'], 'latlng' => [-6.2, 106.81], 'cca2' => 'ID'],
            ], 200),
            'api.open-meteo.com/*' => Http::response([
                'latitude' => -6.20,
                'longitude' => 106.81,
                'timezone' => 'Asia/Jakarta',
                'current' => [
                    'temperature_2m' => 32.1,
                    'weather_code' => 2,
                    'wind_speed_10m' => 9.3,
                    'rain' => 0.5,
                    'time' => '2026-07-19T15:30',
                ],
                'hourly' => [
                    'precipitation_probability' => [5],
                ],
            ], 200),
        ]);

        // 1. Make request
        $response = $this->actingAs($user)
            ->getJson('/api/weather/Indonesia');

        // 2. Assert response
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.country', 'Indonesia')
            ->assertJsonPath('data.weather_description', 'Partly Cloudy');
    }

    /**
     * Test GET /api/weather/{country} with invalid country returns 404
     */
    public function test_get_weather_summary_by_invalid_country_returns_404(): void
    {
        $user = \App\Models\User::factory()->create();

        Http::fake([
            'restcountries.com/*' => Http::response([], 200),
            'api.open-meteo.com/*' => Http::response([], 200),
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/weather/InvalidCountryXYZ');

        $response->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    /**
     * Test Case-Insensitive country search (Indonesia, indonesia, INDONESIA)
     */
    public function test_case_insensitive_country_search(): void
    {
        $user = \App\Models\User::factory()->create();

        Http::fake([
            'restcountries.com/*' => Http::response([
                ['name' => ['common' => 'Indonesia'], 'latlng' => [-6.2, 106.81], 'cca2' => 'ID'],
            ], 200),
            'api.open-meteo.com/*' => Http::response([
                'latitude' => -6.20,
                'longitude' => 106.81,
                'timezone' => 'Asia/Jakarta',
                'current' => [
                    'temperature_2m' => 30.0,
                    'weather_code' => 1,
                    'wind_speed_10m' => 7.5,
                    'rain' => 0.0,
                    'time' => '2026-07-19T12:00',
                ],
                'hourly' => [
                    'precipitation_probability' => [0],
                ],
            ], 200),
        ]);

        // Test lowercase
        $response1 = $this->actingAs($user)
            ->getJson('/api/weather?country=indonesia');
        $response1->assertStatus(200)
            ->assertJsonPath('data.country', 'Indonesia');

        // Test uppercase
        $response2 = $this->actingAs($user)
            ->getJson('/api/weather?country=INDONESIA');
        $response2->assertStatus(200)
            ->assertJsonPath('data.country', 'Indonesia');

        // Test mixed case
        $response3 = $this->actingAs($user)
            ->getJson('/api/weather/InDoNeSiA');
        $response3->assertStatus(200)
            ->assertJsonPath('data.country', 'Indonesia');
    }

    /**
     * Test API response format includes all required fields
     */
    public function test_weather_response_format(): void
    {
        $user = \App\Models\User::factory()->create();

        Http::fake([
            'restcountries.com/*' => Http::response([
                ['name' => ['common' => 'United States'], 'latlng' => [37.09, -95.71], 'cca2' => 'US'],
            ], 200),
            'api.open-meteo.com/*' => Http::response([
                'latitude' => 37.09,
                'longitude' => -95.71,
                'timezone' => 'America/Chicago',
                'current' => [
                    'temperature_2m' => 25.5,
                    'weather_code' => 0,
                    'wind_speed_10m' => 5.2,
                    'rain' => 0.0,
                    'time' => '2026-07-19T08:30',
                ],
                'hourly' => [
                    'precipitation_probability' => [0],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/weather?country=United States');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success' => [],
                'message' => [],
                'data' => [
                    'country',
                    'temperature',
                    'rain',
                    'wind_speed',
                    'weather_code',
                    'weather_description',
                    'latitude',
                    'longitude',
                    'timezone',
                    'current_time',
                    'updated_at',
                ],
            ]);

        // Verify data types
        $data = $response->json('data');
        $this->assertIsString($data['country']);
        $this->assertIsNumeric($data['temperature']);
        $this->assertIsNumeric($data['rain']);
        $this->assertIsNumeric($data['wind_speed']);
        $this->assertIsNumeric($data['weather_code']);
        $this->assertIsString($data['weather_description']);
        $this->assertIsNumeric($data['latitude']);
        $this->assertIsNumeric($data['longitude']);
        $this->assertIsString($data['timezone']);
        $this->assertIsString($data['current_time']);
        $this->assertIsString($data['updated_at']);
    }

    /**
     * Test Weather API error handling when Open-Meteo API returns 500
     */
    public function test_weather_api_error_handling_500(): void
    {
        $user = \App\Models\User::factory()->create();

        Http::fake([
            'restcountries.com/*' => Http::response([
                ['name' => ['common' => 'Japan'], 'latlng' => [36.2, 138.25], 'cca2' => 'JP'],
            ], 200),
            'api.open-meteo.com/*' => Http::response([], 500),
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/weather?country=Japan');

        // Should return error but might have backup cache if available
        $response->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    /**
     * Test Response includes HTTP timestamps
     */
    public function test_response_includes_timestamps(): void
    {
        $user = \App\Models\User::factory()->create();

        Http::fake([
            'restcountries.com/*' => Http::response([
                ['name' => ['common' => 'Brazil'], 'latlng' => [-14.24, -51.93], 'cca2' => 'BR'],
            ], 200),
            'api.open-meteo.com/*' => Http::response([
                'latitude' => -14.24,
                'longitude' => -51.93,
                'timezone' => 'America/Sao_Paulo',
                'current' => [
                    'temperature_2m' => 22.5,
                    'weather_code' => 1,
                    'wind_speed_10m' => 4.1,
                    'rain' => 0.1,
                    'time' => '2026-07-19T18:45',
                ],
                'hourly' => [
                    'precipitation_probability' => [10],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/weather?country=Brazil');

        $response->assertStatus(200);
        $data = $response->json('data');

        // Verify timestamps exist and are in valid format
        $this->assertNotEmpty($data['current_time']);
        $this->assertNotEmpty($data['updated_at']);
        // updated_at should be ISO8601 format
        $this->assertStringContainsString('T', $data['updated_at']);
    }
}
