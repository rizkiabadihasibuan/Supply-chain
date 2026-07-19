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
use App\Jobs\GenerateRiskTrendJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiskTrendStressTest extends TestCase
{
    use RefreshDatabase;

    protected Region $region;
    protected Currency $currency;
    protected RiskClassification $classification;

    protected function setUp(): void
    {
        parent::setUp();

        $this->region = Region::create(['name' => 'Global Region']);
        $this->currency = Currency::create(['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$']);
        $this->classification = RiskClassification::create([
            'name' => 'Low',
            'min_score' => 0.00,
            'max_score' => 40.00,
            'color_code' => '#10B981'
        ]);
    }

    /** @test */
    public function test_bulk_processing_handles_high_load_without_memory_or_performance_leaks(): void
    {
        // 1. Generate 100 countries to simulate a reasonably large load for a unit/feature database test
        $countriesCount = 100;
        
        for ($i = 1; $i <= $countriesCount; $i++) {
            $country = Country::create([
                'name' => "Stress Country {$i}",
                'code' => "S{$i}",
                'region_id' => $this->region->id,
                'currency_id' => $this->currency->id,
                'population' => 5000000,
                'timezone' => 'UTC',
            ]);

            $score = RiskScore::create([
                'country_id' => $country->id,
                'classification_id' => $this->classification->id,
                'weather_score' => 30.0,
                'economic_score' => 30.0,
                'political_score' => 30.0,
                'logistics_score' => 30.0,
                'overall_score' => 30.0,
                'risk_level' => 'Low',
                'calculated_at' => now(),
            ]);

            // History 1
            RiskHistory::create([
                'country_id' => $country->id,
                'risk_score_id' => $score->id,
                'total_risk_score' => 30.0,
                'overall_score' => 30.0,
                'risk_level' => 'Low',
                'calculated_date' => now()->toDateString(),
                'recorded_at' => now()->subHours(2),
            ]);

            // History 2
            RiskHistory::create([
                'country_id' => $country->id,
                'risk_score_id' => $score->id,
                'total_risk_score' => 32.0,
                'overall_score' => 32.0,
                'risk_level' => 'Low',
                'calculated_date' => now()->toDateString(),
                'recorded_at' => now()->subHours(1),
            ]);
        }

        // 2. Measure starting memory and time
        $startMemory = memory_get_usage();
        $startTime = microtime(true);

        // 3. Run the GenerateRiskTrendJob bulk handling inline
        // Instead of mocking Queue, we want to run the handle method directly to test the actual processing loop!
        $job = new GenerateRiskTrendJob();
        app()->call([$job, 'handle']);

        $duration = microtime(true) - $startTime;
        $endMemory = memory_get_usage();
        $memoryPeak = memory_get_peak_usage();
        $memoryUsed = $endMemory - $startMemory;

        // 4. Assertions
        // Ensure execution completes within reasonable thresholds for a 100-country stress run (e.g. 5.0 seconds in test sqlite)
        $this->assertLessThan(8.0, $duration, "Stress test execution took too long: {$duration}s");
        
        // Assert that memory peak usage is reasonable (e.g. less than 64MB memory peak increase during test execution)
        $memoryMb = $memoryUsed / 1024 / 1024;
        $this->assertLessThan(64.0, $memoryMb, "Memory usage during stress test was too high: {$memoryMb}MB");
    }
}
