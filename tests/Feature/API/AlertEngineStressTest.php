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
use App\Jobs\GenerateAlertJob;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlertEngineStressTest extends TestCase
{
    use RefreshDatabase;

    protected Region $region;
    protected Currency $currency;
    protected RiskClassification $classification;

    protected function setUp(): void
    {
        parent::setUp();

        $this->region = Region::create(['name' => 'Stress Region']);
        $this->currency = Currency::create(['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$']);
        $this->classification = RiskClassification::create([
            'name' => 'Low',
            'min_score' => 0.00,
            'max_score' => 40.00,
            'color_code' => '#10B981'
        ]);
    }

    /** @test */
    public function test_alert_engine_processes_bulk_stress_load_efficiently(): void
    {
        $countriesCount = 100;

        // Generate countries, scores, trends
        for ($i = 1; $i <= $countriesCount; $i++) {
            $country = Country::create([
                'name' => "Stress Country {$i}",
                'code' => "S{$i}",
                'region_id' => $this->region->id,
                'currency_id' => $this->currency->id,
                'population' => 1000000,
                'timezone' => 'UTC',
            ]);

            RiskScore::create([
                'country_id' => $country->id,
                'classification_id' => $this->classification->id,
                'weather_score' => 80.0, // triggers alert
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
        }

        $startMemory = memory_get_usage();
        $startTime = microtime(true);

        // Run GenerateAlertJob bulk inline
        $job = new GenerateAlertJob();
        app()->call([$job, 'handle']);

        $duration = microtime(true) - $startTime;
        $endMemory = memory_get_usage();
        $memoryMb = ($endMemory - $startMemory) / 1024 / 1024;

        // Ensure 100 countries processed within 5 seconds
        $this->assertLessThan(5.0, $duration, "Stress test took too long: {$duration}s");

        // Ensure memory usage is stable (under 64MB increase)
        $this->assertLessThan(64.0, $memoryMb, "Memory peak usage was too high: {$memoryMb}MB");
    }
}
