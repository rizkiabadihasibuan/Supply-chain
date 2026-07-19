<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskHistory;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use App\Services\RiskTrendService;
use App\Jobs\GenerateRiskTrendJob;
use App\DTOs\RiskTrendDTO;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiskTrendTest extends TestCase
{
    use RefreshDatabase;

    protected Country $germany;
    protected Region $europe;

    protected function setUp(): void
    {
        parent::setUp();

        $this->europe = Region::create(['name' => 'Europe']);
        $currency = Currency::create(['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€']);
        
        $this->germany = Country::create([
            'name' => 'Germany',
            'code' => 'DE',
            'region_id' => $this->europe->id,
            'currency_id' => $currency->id,
            'population' => 83000000,
            'timezone' => 'Europe/Berlin',
        ]);

        $classification = RiskClassification::create(['name' => 'Low', 'min_score' => 0.00, 'max_score' => 40.00, 'color_code' => '#10B981']);

        // Create mock RiskScores to satisfy foreign key constraints
        $score = RiskScore::create([
            'country_id' => $this->germany->id,
            'classification_id' => $classification->id,
            'weather_score' => 45.0,
            'economic_score' => 45.0,
            'political_score' => 45.0,
            'logistics_score' => 45.0,
            'overall_score' => 45.0,
            'risk_level' => 'Low',
            'calculated_at' => now()->subHours(1),
        ]);

        // Create 2 history entries for Germany:
        // Previous (recorded 2 hours ago): Score = 35.0
        RiskHistory::create([
            'country_id' => $this->germany->id,
            'risk_score_id' => $score->id,
            'total_risk_score' => 35.0,
            'overall_score' => 35.0,
            'risk_level' => 'Low',
            'calculated_date' => now()->toDateString(),
            'recorded_at' => now()->subHours(2),
        ]);

        // Current (recorded 1 hour ago): Score = 45.0
        RiskHistory::create([
            'country_id' => $this->germany->id,
            'risk_score_id' => $score->id,
            'total_risk_score' => 45.0,
            'overall_score' => 45.0,
            'risk_level' => 'Low',
            'calculated_date' => now()->toDateString(),
            'recorded_at' => now()->subHours(1),
        ]);
    }

    /** @test */
    public function test_it_analyzes_risk_trend_correctly(): void
    {
        Config::set('risk.trend_thresholds', [
            'weak' => 2.0,
            'moderate' => 5.0,
            'strong' => 10.0,
        ]);

        $service = app(RiskTrendService::class);
        $dto = $service->analyzeForCountry($this->germany, true);

        // Expected current: 45.0
        // Expected previous: 35.0
        // Expected difference: 10.0
        // Expected percentage change: (10 / 35) * 100 = 28.57%
        // Expected direction: Up
        // Expected strength: Strong (since difference is 10.0, which <= 10.0 (strong threshold) but > 5.0 (moderate))
        
        $this->assertInstanceOf(RiskTrendDTO::class, $dto);
        $this->assertEquals('Germany', $dto->countryName);
        $this->assertEquals(45.0, $dto->currentScore);
        $this->assertEquals(35.0, $dto->previousScore);
        $this->assertEquals(10.0, $dto->scoreDifference);
        $this->assertEquals(28.57, $dto->percentageChange);
        $this->assertEquals('Up', $dto->trendDirection);
        $this->assertEquals('Strong', $dto->trendStrength);

        // Verify rank computed dynamically (only 1 country exists, so ranks are 1 -> 1, difference 0)
        $this->assertEquals(1, $dto->currentRank);
        $this->assertEquals(1, $dto->previousRank);
        $this->assertEquals(0, $dto->rankDifference);

        // Verify database is populated
        $this->assertDatabaseHas('risk_trends', [
            'country_id' => $this->germany->id,
            'current_score' => 45.0,
            'previous_score' => 35.0,
            'change_percentage' => 28.57,
            'trend_direction' => 'Up',
        ]);
    }

    /** @test */
    public function test_job_dispatches_properly(): void
    {
        Queue::fake();

        GenerateRiskTrendJob::dispatch($this->germany->id);
        Queue::assertPushed(GenerateRiskTrendJob::class);
    }
}
