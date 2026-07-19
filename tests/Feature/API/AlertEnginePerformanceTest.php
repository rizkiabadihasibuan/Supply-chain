<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\RiskTrend;
use App\Models\RiskClassification;
use App\Services\AlertEngineService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlertEnginePerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected Region $region;
    protected Currency $currency;
    protected RiskClassification $classification;
    protected AlertEngineService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->region = Region::create(['name' => 'Asia']);
        $this->currency = Currency::create(['code' => 'IDR', 'name' => 'Rupiah', 'symbol' => 'Rp']);
        $this->classification = RiskClassification::create([
            'name' => 'Low',
            'min_score' => 0.00,
            'max_score' => 40.00,
            'color_code' => '#10B981'
        ]);

        $this->service = app(AlertEngineService::class);
    }

    /** @test */
    public function test_alert_engine_performance_under_load(): void
    {
        $countries = [];

        // Create 20 mock countries, each with Score and Trend
        for ($i = 1; $i <= 20; $i++) {
            $country = Country::create([
                'name' => "Country {$i}",
                'code' => "C{$i}",
                'region_id' => $this->region->id,
                'currency_id' => $this->currency->id,
                'population' => 1000000,
                'timezone' => 'UTC',
            ]);

            $score = RiskScore::create([
                'country_id' => $country->id,
                'classification_id' => $this->classification->id,
                'weather_score' => 75.0, // trigger Weather Alert
                'economic_score' => 20.0,
                'political_score' => 20.0,
                'logistics_score' => 20.0,
                'overall_score' => 30.0,
                'final_risk_score' => 30.0,
                'risk_level' => 'Low',
                'calculated_at' => now(),
            ]);

            RiskTrend::create([
                'country_id' => $country->id,
                'trend_type' => 'overall',
                'previous_score' => 30.0,
                'current_score' => 30.0,
                'change_percentage' => 0.0,
                'trend_direction' => 'Stable',
                'analyzed_at' => now(),
            ]);

            $countries[] = $country;
        }

        // Measure Cold Run (evaluating rules, saving to DB, caching)
        Cache::flush();
        $startTime = microtime(true);
        
        foreach ($countries as $country) {
            $this->service->generateAlertsForCountry($country);
        }
        
        $durationCold = microtime(true) - $startTime;

        // Measure Warm Run (retrieving from cache)
        $startTimeWarm = microtime(true);
        
        foreach ($countries as $country) {
            $this->service->remember($country);
        }
        
        $durationWarm = microtime(true) - $startTimeWarm;

        // Assertions
        // 20 countries cold run should be under 1.5 seconds in sqlite in-memory
        $this->assertLessThan(1.5, $durationCold, "Cold alert generation took too long: {$durationCold}s");

        // 20 countries warm run should be under 100ms
        $this->assertLessThan(0.1, $durationWarm, "Warm alert retrieval took too long: {$durationWarm}s");
    }
}
