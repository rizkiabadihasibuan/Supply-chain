<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\RiskSnapshot;
use App\DTOs\RiskTrendDTO;
use App\Services\AlertRuleService;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlertRuleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Country $indonesia;
    protected RiskScore $score;
    protected RiskTrendDTO $trend;
    protected AlertRuleService $service;

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

        $this->score = new RiskScore([
            'country_id' => $this->indonesia->id,
            'weather_score' => 25.0,
            'economic_score' => 25.0,
            'political_score' => 25.0,
            'logistics_score' => 25.0,
            'overall_score' => 25.0,
            'final_risk_score' => 25.0,
            'risk_level' => 'Low',
        ]);

        $this->trend = new RiskTrendDTO([
            'countryName' => 'Indonesia',
            'currentScore' => 25.0,
            'previousScore' => 25.0,
            'scoreDifference' => 0.0,
            'percentageChange' => 0.0,
            'previousRank' => 5,
            'currentRank' => 5,
            'rankDifference' => 0,
            'classificationDifference' => 0,
            'trendDirection' => 'Stable',
            'trendStrength' => 'Weak',
        ]);

        $this->service = app(AlertRuleService::class);
    }

    /** @test */
    public function test_it_triggers_overall_risk_alert_on_high_score(): void
    {
        $this->score->final_risk_score = 80.0; // overall threshold is 75.0
        
        $alerts = $this->service->evaluateRules($this->indonesia, $this->score, $this->trend);
        
        $this->assertNotEmpty($alerts);
        $this->assertEquals('Overall Risk Alert', $alerts[0]['alert_type']);
        $this->assertEquals('High', $alerts[0]['severity']);
    }

    /** @test */
    public function test_it_triggers_sub_component_alerts_on_high_scores(): void
    {
        $this->score->weather_score = 75.0; // weather threshold is 70.0
        
        $alerts = $this->service->evaluateRules($this->indonesia, $this->score, $this->trend);
        
        $this->assertNotEmpty($alerts);
        $this->assertEquals('Weather Alert', $alerts[0]['alert_type']);
        $this->assertEquals('Medium', $alerts[0]['severity']);
    }

    /** @test */
    public function test_it_triggers_trend_and_ranking_alerts(): void
    {
        $trendDTO = new RiskTrendDTO([
            'countryName' => 'Indonesia',
            'currentScore' => 45.0,
            'previousScore' => 25.0,
            'scoreDifference' => 20.0,
            'percentageChange' => 80.0, // trend threshold is 15.0%
            'previousRank' => 1,
            'currentRank' => 6, // drop by 5 positions, ranking threshold is 3
            'rankDifference' => -5,
            'classificationDifference' => 2, // low to medium/high, classification change is enabled
            'trendDirection' => 'Up',
            'trendStrength' => 'Strong',
        ]);

        $alerts = $this->service->evaluateRules($this->indonesia, $this->score, $trendDTO);
        
        $types = array_column($alerts, 'alert_type');
        $this->assertContains('Trend Alert', $types);
        $this->assertContains('Ranking Alert', $types);
        $this->assertContains('Classification Alert', $types);
    }

    /** @test */
    public function test_it_triggers_data_quality_alert_on_missing_snapshot_fields(): void
    {
        $snapshot = new RiskSnapshot([
            'country_id' => $this->indonesia->id,
            'weather_data' => [], // empty
            'economic_data' => ['inflation' => 2.5],
            'news_data' => [], // empty
            'port_data' => [], // empty
        ]);

        $alerts = $this->service->evaluateRules($this->indonesia, $this->score, $this->trend, $snapshot);
        
        $types = array_column($alerts, 'alert_type');
        $this->assertContains('Data Quality Alert', $types);
    }
}
