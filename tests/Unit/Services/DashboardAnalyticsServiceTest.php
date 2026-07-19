<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use App\Services\DashboardAnalyticsService;
use App\Exceptions\MissingRiskDataException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardAnalyticsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Country $germany;
    protected DashboardAnalyticsService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $europe = Region::create(['name' => 'Europe']);
        $usd = Currency::create(['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$']);
        $this->germany = Country::create([
            'name' => 'Germany',
            'code' => 'DE',
            'region_id' => $europe->id,
            'currency_id' => $usd->id,
            'population' => 83000000,
        ]);

        $this->service = app(DashboardAnalyticsService::class);
    }

    /** @test */
    public function test_math_statistics_helpers(): void
    {
        $numbers = [10.0, 20.0, 30.0, 40.0, 50.0];

        $median = $this->service->calculateMedian($numbers);
        $stdDev = $this->service->calculateStandardDeviation($numbers);

        $this->assertEquals(30.0, $median);
        $this->assertEquals(14.142135623730951, $stdDev);
    }

    /** @test */
    public function test_it_throws_exception_if_no_scores_for_global_summary(): void
    {
        $this->expectException(MissingRiskDataException::class);
        $this->service->getGlobalSummary([]);
    }

    /** @test */
    public function test_caching_and_selective_forgetting(): void
    {
        $classification = RiskClassification::create([
            'name' => 'Low',
            'min_score' => 0.00,
            'max_score' => 40.00,
            'color_code' => '#10B981',
        ]);

        // Create Score
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

        Cache::flush();

        // 1. Get Summary (Cache Miss)
        $dto1 = $this->service->getGlobalSummary([]);
        $this->assertEquals(20.0, $dto1->data['global_average_score']);

        // Check registry
        $registry = Cache::get(DashboardAnalyticsService::REGISTRY_KEY, []);
        $this->assertNotEmpty($registry);

        // 2. Forget cache
        $this->service->forget();
        
        $this->assertFalse(Cache::has($registry[0]));
        $this->assertFalse(Cache::has(DashboardAnalyticsService::REGISTRY_KEY));
    }
}
