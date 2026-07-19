<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskHistory;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use App\Services\RiskTrendService;
use App\Exceptions\RiskHistoryNotFoundException;
use App\Exceptions\IncompleteRiskDataException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiskTrendServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Country $indonesia;
    protected RiskClassification $lowClass;
    protected RiskClassification $highClass;
    protected RiskScore $score;

    protected function setUp(): void
    {
        parent::setUp();

        $region = Region::create(['name' => 'Asia']);
        $currency = Currency::create(['code' => 'IDR', 'name' => 'Rupiah', 'symbol' => 'Rp']);
        
        $this->indonesia = Country::create([
            'name' => 'Indonesia',
            'code' => 'ID',
            'region_id' => $region->id,
            'currency_id' => $currency->id,
            'population' => 273000000,
            'timezone' => 'Asia/Jakarta',
        ]);

        $this->lowClass = RiskClassification::create([
            'name' => 'Low',
            'min_score' => 0.00,
            'max_score' => 40.00,
            'color_code' => '#10B981'
        ]);

        $this->highClass = RiskClassification::create([
            'name' => 'High',
            'min_score' => 60.01,
            'max_score' => 80.00,
            'color_code' => '#EF4444'
        ]);

        $this->score = RiskScore::create([
            'country_id' => $this->indonesia->id,
            'classification_id' => $this->lowClass->id,
            'weather_score' => 10.0,
            'economic_score' => 10.0,
            'political_score' => 10.0,
            'logistics_score' => 10.0,
            'overall_score' => 10.0,
            'risk_level' => 'Low',
            'calculated_at' => now(),
        ]);
    }

    /** @test */
    public function test_it_throws_exception_if_no_history_found(): void
    {
        $service = app(RiskTrendService::class);

        $this->expectException(RiskHistoryNotFoundException::class);
        $service->analyzeForCountry($this->indonesia, true);
    }

    /** @test */
    public function test_it_throws_exception_if_history_has_only_one_entry(): void
    {
        RiskHistory::create([
            'country_id' => $this->indonesia->id,
            'risk_score_id' => $this->score->id,
            'total_risk_score' => 30.0,
            'overall_score' => 30.0,
            'risk_level' => 'Low',
            'calculated_date' => now()->toDateString(),
            'recorded_at' => now()->subHours(1),
        ]);

        $service = app(RiskTrendService::class);

        $this->expectException(IncompleteRiskDataException::class);
        $service->analyzeForCountry($this->indonesia, true);
    }

    /** @test */
    public function test_it_calculates_classification_difference_and_division_by_zero_safely(): void
    {
        // History 1: previous score = 0.0 (Low level)
        RiskHistory::create([
            'country_id' => $this->indonesia->id,
            'risk_score_id' => $this->score->id,
            'total_risk_score' => 0.0,
            'overall_score' => 0.0,
            'risk_level' => 'Low',
            'calculated_date' => now()->toDateString(),
            'recorded_at' => now()->subHours(2),
        ]);

        // History 2: current score = 75.0 (High level)
        RiskHistory::create([
            'country_id' => $this->indonesia->id,
            'risk_score_id' => $this->score->id,
            'total_risk_score' => 75.0,
            'overall_score' => 75.0,
            'risk_level' => 'High',
            'calculated_date' => now()->toDateString(),
            'recorded_at' => now()->subHours(1),
        ]);

        $service = app(RiskTrendService::class);
        $dto = $service->analyzeForCountry($this->indonesia, true);

        // Low tier index = 2, High tier index = 4
        // Classification difference: 4 - 2 = 2
        $this->assertEquals(2, $dto->classificationDifference);
        
        // Division by zero check: previous score is 0.0, percentage change must be 0.0
        $this->assertEquals(0.0, $dto->percentageChange);
    }

    /** @test */
    public function test_it_supports_cache_remember_forget_and_refresh(): void
    {
        // Seed 2 histories to allow successful analysis
        RiskHistory::create([
            'country_id' => $this->indonesia->id,
            'risk_score_id' => $this->score->id,
            'total_risk_score' => 20.0,
            'overall_score' => 20.0,
            'risk_level' => 'Low',
            'calculated_date' => now()->toDateString(),
            'recorded_at' => now()->subHours(2),
        ]);

        RiskHistory::create([
            'country_id' => $this->indonesia->id,
            'risk_score_id' => $this->score->id,
            'total_risk_score' => 25.0,
            'overall_score' => 25.0,
            'risk_level' => 'Low',
            'calculated_date' => now()->toDateString(),
            'recorded_at' => now()->subHours(1),
        ]);

        $service = app(RiskTrendService::class);
        
        // Ensure cache is populated
        $dto = $service->remember($this->indonesia);
        $cacheKey = "risk_trend_country_" . strtoupper($this->indonesia->code);
        $this->assertTrue(Cache::has($cacheKey));

        // Test forget
        $this->assertTrue($service->forget($this->indonesia));
        $this->assertFalse(Cache::has($cacheKey));

        // Test refresh
        $dtoRefreshed = $service->refresh($this->indonesia);
        $this->assertTrue(Cache::has($cacheKey));
        $this->assertEquals(25.0, $dtoRefreshed->currentScore);
    }
}
