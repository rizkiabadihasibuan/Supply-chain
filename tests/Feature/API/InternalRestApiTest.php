<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InternalRestApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Country $germany;

    protected function setUp(): void
    {
        parent::setUp();

        \Illuminate\Support\Facades\Cache::flush();

        $this->user = User::factory()->create();

        $europe = Region::create(['name' => 'Europe']);
        $usd = Currency::create(['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$']);
        $this->germany = Country::create([
            'name' => 'Germany',
            'code' => 'DE',
            'region_id' => $europe->id,
            'currency_id' => $usd->id,
            'population' => 83000000,
        ]);

        $this->germany->coordinates()->create([
            'latitude' => 52.5200,
            'longitude' => 13.4050,
        ]);

        $classification = RiskClassification::create([
            'name' => 'Low',
            'min_score' => 0.00,
            'max_score' => 40.00,
            'color_code' => '#10B981',
        ]);

        RiskScore::create([
            'country_id' => $this->germany->id,
            'classification_id' => $classification->id,
            'weather_score' => 20.0,
            'economic_score' => 20.0,
            'political_score' => 20.0,
            'logistics_score' => 20.0,
            'overall_score' => 20.0,
            'final_risk_score' => 20.0,
            'risk_level' => 'Low',
            'calculated_at' => now(),
        ]);
    }

    /** @test */
    public function test_non_prefixed_api_endpoints_succeed(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'api.open-meteo.com/*' => \Illuminate\Support\Facades\Http::response([
                'latitude' => 52.52,
                'longitude' => 13.41,
                'timezone' => 'UTC',
                'current' => [
                    'temperature_2m' => 20.5,
                    'weather_code' => 0,
                    'wind_speed_10m' => 12.3,
                    'rain' => 0.0,
                    'time' => '2026-07-19T13:30',
                ],
                'hourly' => [
                    'precipitation_probability' => [0],
                ],
            ], 200),
            'restcountries.com/*' => \Illuminate\Support\Facades\Http::response([
                [
                    'name' => [
                        'common' => 'Germany',
                        'official' => 'Federal Republic of Germany',
                    ],
                    'cca2' => 'DE',
                    'cca3' => 'DEU',
                    'region' => 'Europe',
                    'subregion' => 'Western Europe',
                    'capital' => ['Berlin'],
                    'population' => 83240525,
                    'area' => 357114.0,
                    'latlng' => [51.0, 9.0],
                    'timezones' => ['UTC+01:00'],
                    'currencies' => [
                        'EUR' => [
                            'name' => 'Euro',
                            'symbol' => '€',
                        ]
                    ],
                    'languages' => [
                        'deu' => 'German',
                    ],
                    'flags' => [
                        'png' => 'https://flagcdn.com/w320/de.png',
                        'svg' => 'https://flagcdn.com/de.svg',
                    ]
                ]
            ], 200)
        ]);

        $this->actingAs($this->user);

        // GET /api/countries
        $response = $this->getJson('/api/countries');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'country',
                    'official_name',
                    'iso2',
                    'iso3',
                    'currency',
                    'currency_symbol',
                    'capital',
                    'region',
                    'subregion',
                    'population',
                    'area',
                    'latitude',
                    'longitude',
                    'timezone',
                    'flag',
                    'updated_at',
                ]
            ]
        ]);

        // GET /api/countries/search?q=germany
        $response = $this->getJson('/api/countries/search?q=germany');
        $response->assertStatus(200);

        // GET /api/countries/DE
        $response = $this->getJson('/api/countries/DE');
        $response->assertStatus(200);
        $response->assertJsonPath('data.country', 'Germany');

        // GET /api/countries/DE/coordinates
        $response = $this->getJson('/api/countries/DE/coordinates');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => ['latitude', 'longitude']
        ]);

        // GET /api/countries/DE/currency
        $response = $this->getJson('/api/countries/DE/currency');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => ['currency', 'currency_symbol']
        ]);

        // GET /api/risk
        $response = $this->getJson('/api/risk');
        $response->assertStatus(200);

        // GET /api/ports
        $response = $this->getJson('/api/ports');
        $response->assertStatus(200);

        // GET /api/news
        $response = $this->getJson('/api/news');
        $response->assertStatus(200);

        // GET /api/currency
        $response = $this->getJson('/api/currency');
        $response->assertStatus(200);

        // GET /api/weather (Open-Meteo)
        $response = $this->getJson('/api/weather?country=DE');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
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
                'updated_at',
            ]
        ]);
    }
}
