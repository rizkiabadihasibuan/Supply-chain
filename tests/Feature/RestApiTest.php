<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Port;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RestApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $country;
    protected $port;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & lexicons
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'LexiconSeeder']);

        $analystRole = Role::where('name', 'Analyst')->first();
        $this->user = User::factory()->create([
            'role_id' => $analystRole->id
        ]);

        // Create country
        $this->country = Country::create([
            'code' => 'DE',
            'name' => 'Germany',
            'currency_code' => 'EUR',
            'currency_name' => 'Euro',
            'region' => 'Europe',
            'language' => 'German',
            'gdp' => 4400000000000,
            'inflation' => 2.1,
            'population' => 84000000,
            'current_weather_temp' => 18.5,
            'current_weather_condition' => 'Partly Cloudy',
            'current_weather_storm_risk' => 10.0,
        ]);

        // Create a port in Germany
        $this->port = Port::create([
            'country_id' => $this->country->id,
            'port_code' => 'DEHAM',
            'name' => 'Port of Hamburg',
            'latitude' => 53.54,
            'longitude' => 9.92,
            'waiting_time_hours' => 12,
            'congestion_rate' => 35.5,
        ]);

        // Setup currency volatility
        Cache::forever('currency_rate_USD_EUR', ['rate' => 0.92, 'volatility' => 1.25]);

        // Configure GNEWS key for HTTP faking
        putenv('GNEWS_API_KEY=testkey');
    }

    /**
     * Test GET /api/countries returns list of countries.
     */
    public function test_api_countries_endpoint(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('api.countries'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'code',
                        'name',
                        'region',
                        'latest_risk'
                    ]
                ]
            ]);
    }

    /**
     * Test GET /api/risk returns risk score details.
     */
    public function test_api_risk_endpoint(): void
    {
        Http::fake([
            'https://gnews.io/api/v4/search*' => Http::response([
                'articles' => [
                    ['title' => 'Germany trade crisis', 'description' => 'Disruption and delay.']
                ]
            ], 200)
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('api.risk', ['country' => 'DE']));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'country_code' => 'DE',
                    'country_name' => 'Germany',
                    'weather_risk' => 10.0,
                    'risk_level' => 'Medium'
                ]
            ]);
    }

    /**
     * Test GET /api/ports returns port coordinates.
     */
    public function test_api_ports_endpoint(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('api.ports', ['country' => 'DE']));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'port_code' => 'DEHAM',
                'name' => 'Port of Hamburg',
                'latitude' => 53.54,
                'longitude' => 9.92,
            ]);
    }

    /**
     * Test GET /api/news returns analyzed sentiment articles.
     */
    public function test_api_news_endpoint(): void
    {
        Http::fake([
            'https://gnews.io/api/v4/search*' => Http::response([
                'articles' => [
                    [
                        'title' => 'German economic recovery stable', // positive: recovery, stable
                        'description' => 'Growth reported.', // positive: growth
                    ]
                ]
            ], 200)
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('api.news', ['country' => 'DE']));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'country_code' => 'DE',
                    'sentiment_breakdown' => [
                        'positive' => 100.0,
                        'neutral' => 0.0,
                        'negative' => 0.0,
                    ]
                ]
            ]);
    }

    /**
     * Test GET /api/currency returns current rate and simulated 7-day history.
     */
    public function test_api_currency_endpoint(): void
    {
        Http::fake([
            'https://open.er-api.com/v6/latest/USD' => Http::response([
                'rates' => [
                    'EUR' => 0.92
                ]
            ], 200)
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('api.currency', ['country' => 'DE']));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'currency_code' => 'EUR',
                    'current_rate' => 0.92,
                    'volatility' => 1.25
                ]
            ])
            ->assertJsonCount(7, 'data.history');
    }

    /**
     * Test watchlist toggle and list endpoints.
     */
    public function test_watchlist_endpoints(): void
    {
        // 1. Toggle country to add it
        $toggleResponse = $this->actingAs($this->user)
            ->postJson(route('api.watchlist.toggle', ['country_code' => 'DE']));

        $toggleResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'attached' => true
            ]);

        $this->assertDatabaseHas('watchlists', [
            'user_id' => $this->user->id,
            'country_id' => $this->country->id
        ]);

        // 2. Fetch watchlist
        $listResponse = $this->actingAs($this->user)
            ->getJson(route('api.watchlist'));

        $listResponse->assertStatus(200)
            ->assertJsonFragment([
                'code' => 'DE',
                'name' => 'Germany'
            ]);

        // 3. Toggle again to remove it
        $toggleResponse2 = $this->actingAs($this->user)
            ->postJson(route('api.watchlist.toggle', ['country_code' => 'DE']));

        $toggleResponse2->assertStatus(200)
            ->assertJson([
                'success' => true,
                'attached' => false
            ]);

        $this->assertDatabaseMissing('watchlists', [
            'user_id' => $this->user->id,
            'country_id' => $this->country->id
        ]);
    }
}
