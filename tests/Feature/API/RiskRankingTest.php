<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use App\Services\RiskRankingService;
use App\Jobs\GenerateRiskRankingJob;
use App\DTOs\RiskRankingDTO;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiskRankingTest extends TestCase
{
    use RefreshDatabase;

    protected Country $germany;
    protected Country $france;
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

        $this->france = Country::create([
            'name' => 'France',
            'code' => 'FR',
            'region_id' => $this->europe->id,
            'currency_id' => $currency->id,
            'population' => 67000000,
            'timezone' => 'Europe/Paris',
        ]);

        // Seed classifications
        $classification = RiskClassification::create(['name' => 'Low', 'min_score' => 0.00, 'max_score' => 40.00, 'color_code' => '#10B981']);
        $classificationHigh = RiskClassification::create(['name' => 'High', 'min_score' => 60.01, 'max_score' => 80.00, 'color_code' => '#EF4444']);

        // Create scores (Germany = 35.0, France = 75.0)
        RiskScore::create([
            'country_id' => $this->germany->id,
            'classification_id' => $classification->id,
            'weather_score' => 35.0,
            'economic_score' => 35.0,
            'political_score' => 35.0,
            'logistics_score' => 35.0,
            'overall_score' => 35.0,
            'risk_level' => 'Low',
            'calculated_at' => now(),
            'source_version' => '1.0.0',
        ]);

        RiskScore::create([
            'country_id' => $this->france->id,
            'classification_id' => $classificationHigh->id,
            'weather_score' => 75.0,
            'economic_score' => 75.0,
            'political_score' => 75.0,
            'logistics_score' => 75.0,
            'overall_score' => 75.0,
            'risk_level' => 'High',
            'calculated_at' => now(),
            'source_version' => '1.0.0',
        ]);
    }

    /** @test */
    public function test_it_generates_risk_rankings_sorted_by_overall_score(): void
    {
        $service = app(RiskRankingService::class);
        $rankings = $service->getRanking([], 'overall_score', 'desc', null, true);

        $this->assertCount(2, $rankings);
        $this->assertInstanceOf(RiskRankingDTO::class, $rankings[0]);
        
        // France (75.0) should be rank 1 (highest risk)
        $this->assertEquals('France', $rankings[0]->countryName);
        $this->assertEquals(1, $rankings[0]->rank);
        
        // Germany (35.0) should be rank 2
        $this->assertEquals('Germany', $rankings[1]->countryName);
        $this->assertEquals(2, $rankings[1]->rank);
    }

    /** @test */
    public function test_it_generates_top_highest_and_lowest(): void
    {
        $service = app(RiskRankingService::class);
        
        $highest = $service->getTopHighest(1, true);
        $this->assertCount(1, $highest);
        $this->assertEquals('France', $highest[0]->countryName);

        $lowest = $service->getTopLowest(1, true);
        $this->assertCount(1, $lowest);
        $this->assertEquals('Germany', $lowest[0]->countryName);
    }

    /** @test */
    public function test_it_filters_by_region(): void
    {
        $service = app(RiskRankingService::class);
        
        // Region matching Europe
        $rankings = $service->getRanking(['region_id' => $this->europe->id], 'overall_score', 'desc', null, true);
        $this->assertCount(2, $rankings);

        // Region non-existent ID
        $rankingsEmpty = $service->getRanking(['region_id' => 999], 'overall_score', 'desc', null, true);
        $this->assertCount(0, $rankingsEmpty);
    }

    /** @test */
    public function test_job_dispatches_properly(): void
    {
        Queue::fake();

        GenerateRiskRankingJob::dispatch([], 'overall_score', 'desc', 10);
        Queue::assertPushed(GenerateRiskRankingJob::class);
    }
}
