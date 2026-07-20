<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $user = User::where('role', 'user')->first();
        $this->actingAs($user);
    }

    public function test_dashboard_summary_endpoint_works(): void
    {
        $response = $this->getJson('/api/v1/dashboard');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'total_countries',
                'total_ports',
                'critical_risks',
                'news_articles_count',
                'global_average_risk_score',
            ]
        ]);
    }

    public function test_top_risk_countries_analytics_endpoint_works(): void
    {
        $response = $this->getJson('/api/v1/analytics/top-risk-countries');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_lowest_risk_countries_analytics_endpoint_works(): void
    {
        $response = $this->getJson('/api/v1/analytics/lowest-risk-countries');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_ports_endpoint_works(): void
    {
        $response = $this->getJson('/api/v1/ports');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_news_endpoint_works(): void
    {
        $response = $this->getJson('/api/v1/news');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_exchange_rate_endpoint_works(): void
    {
        $response = $this->getJson('/api/v1/exchange-rate');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_risk_history_endpoint_works(): void
    {
        $response = $this->getJson('/api/v1/risk/history');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data']);
    }
}
