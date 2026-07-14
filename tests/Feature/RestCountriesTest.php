<?php

namespace Tests\Feature;

use App\Models\ApiRequestLog;
use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use App\Services\RestCountriesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RestCountriesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $country;
    protected $mockResponseData;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        
        $analystRole = Role::where('name', 'Analyst')->first();
        $this->user = User::factory()->create([
            'role_id' => $analystRole->id
        ]);

        // Create a pilot country
        $this->country = Country::create([
            'code' => 'ID',
            'name' => 'Indonesia',
            'currency_code' => 'IDR',
            'currency_name' => 'Indonesian Rupiah',
            'region' => 'Southeast Asia',
            'language' => 'Indonesian',
            'gdp' => 1370000000000,
            'inflation' => 2.8,
            'population' => 277000000,
            'current_weather_temp' => 31.0,
            'current_weather_condition' => 'Heavy Rain',
        ]);

        // Define mock response data from restcountries.com format
        $this->mockResponseData = [
            [
                'name' => ['common' => 'Indonesia'],
                'region' => 'Asia',
                'population' => 277534122,
                'currencies' => [
                    'IDR' => ['name' => 'Indonesian rupiah']
                ],
                'languages' => [
                    'ind' => 'Indonesian'
                ],
                'flags' => [
                    'svg' => 'https://flagcdn.com/id.svg',
                    'png' => 'https://flagcdn.com/w320/id.png'
                ],
                'capital' => ['Jakarta'],
                'latlng' => [-6.0, 120.0],
                'capitalInfo' => [
                    'latlng' => [-6.2147, 106.8451]
                ]
            ]
        ];
    }

    /**
     * Test RestCountriesService fetchByCode method.
     */
    public function test_service_can_fetch_and_parse_country_data(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/ID' => Http::response($this->mockResponseData, 200)
        ]);

        $service = app(RestCountriesService::class);
        $result = $service->fetchByCode('ID');

        $this->assertNotNull($result);
        $this->assertEquals('Indonesia', $result['name']);
        $this->assertEquals('Asia', $result['region']);
        $this->assertEquals(277534122, $result['population']);
        $this->assertEquals('IDR', $result['currency_code']);
        $this->assertEquals('Indonesian rupiah', $result['currency_name']);
        $this->assertEquals('Indonesian', $result['language']);
        $this->assertEquals('Jakarta', $result['capital']);
        $this->assertEquals(-6.2147, $result['latitude']);
        $this->assertEquals(106.8451, $result['longitude']);

        // Check if API call was logged to database
        $this->assertDatabaseHas('activity_logs', [
            'log_type' => 'api_request',
            'description' => 'Panggilan REST Countries API untuk kode ID',
        ]);
    }

    /**
     * Test caching behavior in service.
     */
    public function test_service_caches_responses(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/ID' => Http::response($this->mockResponseData, 200)
        ]);

        $service = app(RestCountriesService::class);
        
        // Clear cache first
        Cache::forget('rest_countries_ID');

        // First call - should trigger HTTP request
        $service->fetchByCode('ID');
        Http::assertSentCount(1);

        // Second call - should fetch from cache without calling HTTP again
        $service->fetchByCode('ID');
        Http::assertSentCount(1);
    }

    /**
     * Test controller country detail route returns JSON.
     */
    public function test_controller_returns_parsed_json_for_authenticated_users(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/ID' => Http::response($this->mockResponseData, 200),
            'http://api.worldbank.org/v2/country/id/indicator/*' => Http::response([[], [['date' => '2023', 'value' => 277534122]]], 200),
            'https://api.open-meteo.com/v1/forecast*' => Http::response([
                'current' => [
                    'temperature_2m' => 31.0,
                    'precipitation' => 0.0,
                    'weather_code' => 0,
                    'wind_speed_10m' => 5.0,
                    'wind_gusts_10m' => 10.0,
                ]
            ], 200),
            'https://open.er-api.com/v6/latest/USD' => Http::response([
                'rates' => [
                    'IDR' => 16000.00
                ]
            ], 200),
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('countries.detail', ['code' => 'ID']));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Indonesia',
                    'region' => 'Asia',
                    'population' => 277534122
                ]
            ]);
    }

    /**
     * Test controller sync updates local database.
     */
    public function test_controller_syncs_data_into_local_database(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/ID' => Http::response($this->mockResponseData, 200),
            'http://api.worldbank.org/v2/country/id/indicator/*' => Http::response([[], [['date' => '2023', 'value' => 277534122]]], 200),
            'https://api.open-meteo.com/v1/forecast*' => Http::response([
                'current' => [
                    'temperature_2m' => 31.0,
                    'precipitation' => 0.0,
                    'weather_code' => 0,
                    'wind_speed_10m' => 5.0,
                    'wind_gusts_10m' => 10.0,
                ]
            ], 200),
            'https://open.er-api.com/v6/latest/USD' => Http::response([
                'rates' => [
                    'IDR' => 16000.00
                ]
            ], 200),
        ]);

        $this->assertEquals(277000000, $this->country->population); // original value

        $response = $this->actingAs($this->user)
            ->postJson(route('countries.sync', ['code' => 'ID']));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => "Data lokal negara 'Indonesia' berhasil disinkronisasikan dengan REST Countries, World Bank, dan Open-Meteo API."
            ]);

        // Reload from DB and assert updated population and region
        $this->country->refresh();
        $this->assertEquals(277534122, $this->country->population);
        $this->assertEquals('Asia', $this->country->region);
        $this->assertEquals(31.0, $this->country->current_weather_temp);
        $this->assertEquals('Clear Sky', $this->country->current_weather_condition);
        $this->assertEquals(0.0, $this->country->current_weather_storm_risk);
    }

    /**
     * Test error handling when REST Countries API returns an error.
     */
    public function test_service_gracefully_handles_api_failure(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/ID' => Http::response('Server Error', 500)
        ]);

        $service = app(RestCountriesService::class);
        Cache::forget('rest_countries_ID');
        
        $result = $service->fetchByCode('ID');

        $this->assertNull($result);

        // Check if failed call was logged with status 500
        $this->assertDatabaseHas('activity_logs', [
            'log_type' => 'api_request',
            'description' => 'Panggilan REST Countries API untuk kode ID',
        ]);
    }
}
