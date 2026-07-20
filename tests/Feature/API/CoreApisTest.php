<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Country;
use App\Models\RiskScore;
use App\Models\Port;
use App\Models\NewsArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoreApisTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed basic database entities
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $user = User::where('role', 'user')->first() ?? User::factory()->create();
        $this->actingAs($user);
    }

    public function test_countries_api_returns_countries_list(): void
    {
        $response = $this->getJson('/api/v1/countries');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_risk_api_returns_risk_scores(): void
    {
        $response = $this->getJson('/api/v1/risk');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_risk_calculation_api(): void
    {
        $country = Country::first();
        $this->assertNotNull($country);

        $response = $this->postJson('/api/v1/risk/calculate', [
            'country_id' => $country->id,
            'weather_score' => 40,
            'inflation_score' => 30,
            'political_score' => 50,
            'currency_score' => 20,
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'country',
                'final_risk_score',
                'risk_level',
            ]
        ]);
    }

    public function test_ports_api(): void
    {
        $response = $this->getJson('/api/v1/ports');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_news_api(): void
    {
        $response = $this->getJson('/api/v1/news');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_currency_api(): void
    {
        $response = $this->getJson('/api/v1/currencies');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data' => ['base_currency', 'rates']]);
    }

    public function test_comparison_api(): void
    {
        $response = $this->getJson('/api/v1/comparison?country_a=SG&country_b=ID');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'country_a',
                'country_b',
            ]
        ]);
    }

    public function test_watchlist_api(): void
    {
        $user = User::where('role', 'user')->first();
        $this->actingAs($user);

        $response = $this->getJson('/api/v1/watchlists');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data']);
    }
}
