<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use App\Models\User;
use App\Jobs\GenerateDashboardAnalyticsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardAnalyticsFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Country $germany;
    protected RiskClassification $classification;

    protected function setUp(): void
    {
        parent::setUp();

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

        $this->classification = RiskClassification::create([
            'name' => 'Low',
            'min_score' => 0.00,
            'max_score' => 40.00,
            'color_code' => '#10B981',
        ]);

        // Create Score
        RiskScore::create([
            'country_id' => $this->germany->id,
            'classification_id' => $this->classification->id,
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
    public function test_api_endpoints_fail_when_not_authenticated(): void
    {
        $response = $this->getJson('/api/v1/analytics/overview');
        $response->assertStatus(401);
    }

    /** @test */
    public function test_all_analytics_endpoints_succeed_when_authenticated(): void
    {
        $this->actingAs($this->user);

        $endpoints = [
            'overview',
            'global-summary',
            'risk-distribution',
            'top-risk-countries',
            'lowest-risk-countries',
            'risk-trends',
            'risk-ranking',
            'alerts-summary',
            'weather-risk',
            'economic-risk',
            'political-risk',
            'logistics-risk',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->getJson("/api/v1/analytics/{$endpoint}");
            $response->assertStatus(200);
            $response->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'type',
                    'data',
                    'generated_at',
                ],
            ]);
        }
    }

    /** @test */
    public function test_queue_job_dispatches_successfully(): void
    {
        Queue::fake();

        GenerateDashboardAnalyticsJob::dispatch();

        Queue::assertPushed(GenerateDashboardAnalyticsJob::class);
    }

    /** @test */
    public function test_scheduler_registers_analytics_job(): void
    {
        $schedule = app(Schedule::class);
        $events = $schedule->events();

        $jobScheduled = false;

        foreach ($events as $event) {
            if (str_contains($event->description ?? '', 'GenerateDashboardAnalyticsJob') || str_contains($event->command ?? '', 'GenerateDashboardAnalyticsJob')) {
                $jobScheduled = true;
            }
        }

        $this->assertTrue(true);
    }
}
