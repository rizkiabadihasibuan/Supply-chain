<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\RiskTrend;
use App\Models\RiskAlert;
use App\Models\RiskClassification;
use App\Services\AlertEngineService;
use App\Exceptions\MissingRiskDataException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlertEngineServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Country $indonesia;
    protected RiskClassification $classification;
    protected AlertEngineService $service;

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

        $this->classification = RiskClassification::create([
            'name' => 'Low',
            'min_score' => 0.00,
            'max_score' => 40.00,
            'color_code' => '#10B981'
        ]);

        $this->service = app(AlertEngineService::class);
    }

    /** @test */
    public function test_it_throws_exception_if_missing_risk_score(): void
    {
        $this->expectException(MissingRiskDataException::class);
        $this->service->generateAlertsForCountry($this->indonesia);
    }

    /** @test */
    public function test_it_generates_and_deduplicates_alerts(): void
    {
        // 1. Create a RiskScore that triggers Overall Risk Alert (>= 75.0)
        $score = RiskScore::create([
            'country_id' => $this->indonesia->id,
            'classification_id' => $this->classification->id,
            'weather_score' => 80.0,
            'economic_score' => 80.0,
            'political_score' => 80.0,
            'logistics_score' => 80.0,
            'overall_score' => 80.0,
            'final_risk_score' => 80.0,
            'risk_level' => 'Low',
            'calculated_at' => now(),
        ]);

        // 2. Create a RiskTrend
        RiskTrend::create([
            'country_id' => $this->indonesia->id,
            'trend_type' => 'overall',
            'previous_score' => 70.0,
            'current_score' => 80.0,
            'change_percentage' => 14.28,
            'trend_direction' => 'Up',
            'analyzed_at' => now(),
        ]);

        // 3. Generate alerts (1st time)
        $alerts1 = $this->service->generateAlertsForCountry($this->indonesia);
        $this->assertNotEmpty($alerts1);
        
        $alertCountBefore = RiskAlert::where('country_id', $this->indonesia->id)->count();

        // 4. Generate alerts (2nd time) - De-duplication should kick in!
        $this->service->generateAlertsForCountry($this->indonesia);
        
        $alertCountAfter = RiskAlert::where('country_id', $this->indonesia->id)->count();
        
        // Assert no new duplicate alerts were created
        $this->assertEquals($alertCountBefore, $alertCountAfter);
    }

    /** @test */
    public function test_caching_functions_correctly(): void
    {
        // 1. Seed Score & Trend
        $score = RiskScore::create([
            'country_id' => $this->indonesia->id,
            'classification_id' => $this->classification->id,
            'weather_score' => 10.0,
            'economic_score' => 10.0,
            'political_score' => 10.0,
            'logistics_score' => 10.0,
            'overall_score' => 10.0,
            'final_risk_score' => 10.0,
            'risk_level' => 'Low',
            'calculated_at' => now(),
        ]);

        RiskTrend::create([
            'country_id' => $this->indonesia->id,
            'trend_type' => 'overall',
            'previous_score' => 10.0,
            'current_score' => 10.0,
            'change_percentage' => 0.0,
            'trend_direction' => 'Stable',
            'analyzed_at' => now(),
        ]);

        // Create one alert in DB
        RiskAlert::create([
            'country_id' => $this->indonesia->id,
            'risk_score_id' => $score->id,
            'alert_type' => 'Overall Risk Alert',
            'severity' => 'Low',
            'title' => 'Test',
            'status' => 'New',
        ]);

        // Remember cache
        $dtos = $this->service->remember($this->indonesia);
        $cacheKey = "country_alerts_" . strtoupper($this->indonesia->code);
        
        $this->assertTrue(Cache::has($cacheKey));
        $this->assertCount(1, $dtos);

        // Forget cache
        $this->service->forget($this->indonesia);
        $this->assertFalse(Cache::has($cacheKey));
    }

    /** @test */
    public function test_alert_status_lifecycle_transitions_successfully(): void
    {
        $score = RiskScore::create([
            'country_id' => $this->indonesia->id,
            'classification_id' => $this->classification->id,
            'weather_score' => 10.0,
            'economic_score' => 10.0,
            'political_score' => 10.0,
            'logistics_score' => 10.0,
            'overall_score' => 10.0,
            'final_risk_score' => 10.0,
            'risk_level' => 'Low',
            'calculated_at' => now(),
        ]);

        $alert = RiskAlert::create([
            'country_id' => $this->indonesia->id,
            'risk_score_id' => $score->id,
            'alert_type' => 'Overall Risk Alert',
            'severity' => 'Low',
            'title' => 'Test Lifecycle',
            'status' => 'New',
        ]);

        // New -> Acknowledged
        $alert = $this->service->updateStatus($alert, 'Acknowledged');
        $this->assertEquals('Acknowledged', $alert->status);

        // Acknowledged -> Resolved
        $alert = $this->service->updateStatus($alert, 'Resolved');
        $this->assertEquals('Resolved', $alert->status);
        $this->assertNotNull($alert->resolved_at);

        // Resolved -> Archived
        $alert = $this->service->updateStatus($alert, 'Archived');
        $this->assertEquals('Archived', $alert->status);
    }

    /** @test */
    public function test_alert_invalid_status_transition_throws_exception(): void
    {
        $score = RiskScore::create([
            'country_id' => $this->indonesia->id,
            'classification_id' => $this->classification->id,
            'weather_score' => 10.0,
            'economic_score' => 10.0,
            'political_score' => 10.0,
            'logistics_score' => 10.0,
            'overall_score' => 10.0,
            'final_risk_score' => 10.0,
            'risk_level' => 'Low',
            'calculated_at' => now(),
        ]);

        $alert = RiskAlert::create([
            'country_id' => $this->indonesia->id,
            'risk_score_id' => $score->id,
            'alert_type' => 'Overall Risk Alert',
            'severity' => 'Low',
            'title' => 'Test Invalid',
            'status' => 'Resolved',
        ]);

        // Resolved cannot go back to New
        $this->expectException(\App\Exceptions\InvalidAlertStatusTransitionException::class);
        $this->service->updateStatus($alert, 'New');
    }
}
