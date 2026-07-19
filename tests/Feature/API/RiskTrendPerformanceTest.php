<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskHistory;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use App\Services\RiskTrendService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiskTrendPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected Region $region;
    protected Currency $currency;
    protected RiskClassification $classification;

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
    }

    /** @test */
    public function test_it_performs_well_with_large_number_of_countries(): void
    {
        $countries = [];
        $service = app(RiskTrendService::class);

        // Create 20 mock countries, each with 2 history records
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
                'weather_score' => 20.0,
                'economic_score' => 20.0,
                'political_score' => 20.0,
                'logistics_score' => 20.0,
                'overall_score' => 20.0,
                'risk_level' => 'Low',
                'calculated_at' => now(),
            ]);

            // History 1
            RiskHistory::create([
                'country_id' => $country->id,
                'risk_score_id' => $score->id,
                'total_risk_score' => 20.0,
                'overall_score' => 20.0,
                'risk_level' => 'Low',
                'calculated_date' => now()->toDateString(),
                'recorded_at' => now()->subHours(2),
            ]);

            // History 2
            RiskHistory::create([
                'country_id' => $country->id,
                'risk_score_id' => $score->id,
                'total_risk_score' => 22.0,
                'overall_score' => 22.0,
                'risk_level' => 'Low',
                'calculated_date' => now()->toDateString(),
                'recorded_at' => now()->subHours(1),
            ]);

            $countries[] = $country;
        }

        // Measure time without Cache (cold run)
        Cache::flush();
        $startTime = microtime(true);
        
        foreach ($countries as $country) {
            $service->analyzeForCountry($country, true); // force refresh to skip cache
        }
        
        $durationCold = microtime(true) - $startTime;
        
        // Measure time with Cache (warm run)
        $startTimeWarm = microtime(true);
        
        foreach ($countries as $country) {
            $service->remember($country); // should fetch from cache
        }
        
        $durationWarm = microtime(true) - $startTimeWarm;

        // Cold run for 20 countries should be reasonable (e.g. less than 2 seconds in test environment)
        $this->assertLessThan(2.0, $durationCold, "Cold execution took too long: {$durationCold}s");

        // Warm run for 20 countries should be extremely fast (e.g. less than 100 milliseconds)
        $this->assertLessThan(0.1, $durationWarm, "Warm execution (cache) took too long: {$durationWarm}s");
    }
}
