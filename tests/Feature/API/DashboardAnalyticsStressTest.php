<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use App\Services\DashboardAnalyticsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardAnalyticsStressTest extends TestCase
{
    use RefreshDatabase;

    protected Region $region;
    protected Currency $currency;
    protected RiskClassification $classification;
    protected DashboardAnalyticsService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->region = Region::create(['name' => 'Stress Region']);
        $this->currency = Currency::create(['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$']);
        $this->classification = RiskClassification::create([
            'name' => 'Low',
            'min_score' => 0.00,
            'max_score' => 40.00,
            'color_code' => '#10B981',
        ]);

        $this->service = app(DashboardAnalyticsService::class);
    }

    /** @test */
    public function test_dashboard_analytics_scales_under_stress_load(): void
    {
        $countriesCount = 100;

        // Generate countries & scores
        for ($i = 1; $i <= $countriesCount; $i++) {
            $country = Country::create([
                'name' => "Stress Country {$i}",
                'code' => "S{$i}",
                'region_id' => $this->region->id,
                'currency_id' => $this->currency->id,
                'population' => 1000000,
            ]);

            RiskScore::create([
                'country_id' => $country->id,
                'classification_id' => $this->classification->id,
                'weather_score' => 50.0,
                'economic_score' => 50.0,
                'political_score' => 50.0,
                'logistics_score' => 50.0,
                'overall_score' => 50.0,
                'final_risk_score' => 50.0,
                'risk_level' => 'Low',
                'calculated_at' => now(),
            ]);
        }

        // 1. Pre-warm Cache
        Cache::flush();
        $this->service->refresh();

        // 2. Perform 50 sequential requests to getOverview and check execution time
        $startTime = microtime(true);
        for ($r = 0; $r < 50; $r++) {
            $this->service->getOverview([]);
            $this->service->getGlobalSummary([]);
            $this->service->getRiskDistribution([]);
        }
        $duration = microtime(true) - $startTime;

        // 150 warm cache hits must execute extremely fast (under 100 milliseconds)
        $this->assertLessThan(0.3, $duration, "Analytics stress cache hit took too long: {$duration}s");
    }
}
