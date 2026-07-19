<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskSnapshot;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use App\Models\RiskHistory;
use App\Services\RiskCalculatorService;
use App\Jobs\CalculateRiskScoreJob;
use App\DTOs\RiskScoreDTO;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiskCalculatorTest extends TestCase
{
    use RefreshDatabase;

    protected Country $country;
    protected RiskSnapshot $snapshot;

    protected function setUp(): void
    {
        parent::setUp();

        $region = Region::create(['name' => 'Europe']);
        $currency = Currency::create(['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€']);
        
        $this->country = Country::create([
            'name' => 'Germany',
            'code' => 'DE',
            'region_id' => $region->id,
            'currency_id' => $currency->id,
            'population' => 83000000,
            'timezone' => 'Europe/Berlin',
        ]);

        $this->snapshot = RiskSnapshot::create([
            'country_id' => $this->country->id,
            'weather_data' => [
                'temperature' => 37.0, // Extreme difference (37 - 22 = 15) -> 15/25 * 100 = 60.0 score
                'wind_speed' => 40.0, // 40/80 * 100 = 50.0 score
                'rainfall' => 35.0, // 35/70 * 100 = 50.0 score
                // Temp (60 * 0.4) + Wind (50 * 0.3) + Rain (50 * 0.3) = 24 + 15 + 15 = 54.0 weather risk score
            ],
            'economic_data' => [
                'inflation' => 10.0, // Risk: 7.5 -> 7.5/15 * 100 = 50.0 score
                'gdp_growth' => 0.0, // Risk: 5.0 -> 5/10 * 100 = 50.0 score
                // Economic Risk = (50 * 0.5) + (50 * 0.5) = 50.0 economic risk score
            ],
            'news_data' => [
                ['title' => 'Severe strike at Hamburg port', 'sentiment' => 'negative'],
                ['title' => 'Inflation remains stable', 'sentiment' => 'neutral'],
            ], // 1 negative out of 2 -> 50.0 political risk score
            'port_data' => [
                'congestion_index' => 5.0, // 5.0 * 10 = 50.0 logistics risk score
            ],
            'overall_status' => 'Normal',
            'snapshot_time' => now(),
        ]);

        // Seed classifications
        RiskClassification::create(['name' => 'Low', 'min_score' => 0.00, 'max_score' => 30.00, 'color_code' => '#10B981']);
        RiskClassification::create(['name' => 'Medium', 'min_score' => 30.01, 'max_score' => 60.00, 'color_code' => '#F59E0B']);
        RiskClassification::create(['name' => 'High', 'min_score' => 60.01, 'max_score' => 80.00, 'color_code' => '#EF4444']);
        RiskClassification::create(['name' => 'Critical', 'min_score' => 80.01, 'max_score' => 100.00, 'color_code' => '#7F1D1D']);
    }

    /** @test */
    public function test_it_calculates_weighted_risk_score_and_saves_it(): void
    {
        // Assert weights setup
        Config::set('risk.weights', [
            'weather' => 0.30,
            'economic' => 0.20,
            'political' => 0.40,
            'logistics' => 0.10,
        ]);

        $service = app(RiskCalculatorService::class);
        $dto = $service->calculateForCountry($this->country, true);

        // Expected Weather Score = 54.0
        // Expected Economic Score = 50.0
        // Expected Political Score = 50.0
        // Expected Logistics Score = 50.0
        // Expected Overall Score = (54 * 0.3) + (50 * 0.2) + (50 * 0.4) + (50 * 0.1)
        //                        = 16.2 + 10.0 + 20.0 + 5.0 = 51.2

        $this->assertInstanceOf(RiskScoreDTO::class, $dto);
        $this->assertEquals(54.0, $dto->weatherScore);
        $this->assertEquals(50.0, $dto->economicScore);
        $this->assertEquals(50.0, $dto->politicalScore);
        $this->assertEquals(50.0, $dto->logisticsScore);
        $this->assertEquals(51.2, $dto->overallScore);
        $this->assertEquals('Medium', $dto->riskLevel);

        // Verify databases
        $this->assertDatabaseHas('risk_scores', [
            'country_id' => $this->country->id,
            'overall_score' => 51.2,
            'risk_level' => 'Medium'
        ]);

        $this->assertDatabaseHas('risk_histories', [
            'country_id' => $this->country->id,
            'overall_score' => 51.2
        ]);
    }

    /** @test */
    public function test_job_dispatches_properly_for_single_and_bulk(): void
    {
        Queue::fake();

        // Single country job
        CalculateRiskScoreJob::dispatch($this->country->id);
        Queue::assertPushed(CalculateRiskScoreJob::class);

        // Bulk country job
        CalculateRiskScoreJob::dispatch();
        Queue::assertPushed(CalculateRiskScoreJob::class);
    }
}
