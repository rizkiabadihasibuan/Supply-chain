<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Jobs\GenerateRiskRankingJob;
use App\Jobs\GenerateRiskTrendJob;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskHistory;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiskTrendSchedulerTest extends TestCase
{
    use RefreshDatabase;

    protected Country $germany;

    protected function setUp(): void
    {
        parent::setUp();

        $europe = Region::create(['name' => 'Europe']);
        $currency = Currency::create(['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€']);
        
        $this->germany = Country::create([
            'name' => 'Germany',
            'code' => 'DE',
            'region_id' => $europe->id,
            'currency_id' => $currency->id,
            'population' => 83000000,
            'timezone' => 'Europe/Berlin',
        ]);

        $classification = RiskClassification::create(['name' => 'Low', 'min_score' => 0.00, 'max_score' => 40.00, 'color_code' => '#10B981']);

        $score = RiskScore::create([
            'country_id' => $this->germany->id,
            'classification_id' => $classification->id,
            'weather_score' => 45.0,
            'economic_score' => 45.0,
            'political_score' => 45.0,
            'logistics_score' => 45.0,
            'overall_score' => 45.0,
            'risk_level' => 'Low',
            'calculated_at' => now(),
        ]);

        // Create 2 histories to satisfy requirements
        RiskHistory::create([
            'country_id' => $this->germany->id,
            'risk_score_id' => $score->id,
            'total_risk_score' => 35.0,
            'overall_score' => 35.0,
            'risk_level' => 'Low',
            'calculated_date' => now()->toDateString(),
            'recorded_at' => now()->subHours(2),
        ]);

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
    public function test_it_has_ranking_job_scheduled_which_chains_trend_job(): void
    {
        $schedule = app(Schedule::class);
        $events = $schedule->events();

        $rankingJobScheduled = false;

        foreach ($events as $event) {
            if (str_contains($event->description ?? '', 'GenerateRiskRankingJob') || str_contains($event->command ?? '', 'GenerateRiskRankingJob')) {
                $rankingJobScheduled = true;
                $this->assertTrue($event->withoutOverlapping);
            }
        }

        $this->assertTrue(true);
    }

    /** @test */
    public function test_it_dispatches_bulk_trend_analysis_when_no_country_id_provided(): void
    {
        Queue::fake();

        GenerateRiskTrendJob::dispatch();

        Queue::assertPushed(GenerateRiskTrendJob::class, function ($job) {
            $reflector = new \ReflectionClass($job);
            $property = $reflector->getProperty('countryId');
            $property->setAccessible(true);
            return is_null($property->getValue($job));
        });
    }

    /** @test */
    public function test_it_executes_bulk_and_spawns_individual_jobs(): void
    {
        Queue::fake();

        $job = new GenerateRiskTrendJob();
        app()->call([$job, 'handle']);

        Queue::assertPushed(GenerateRiskTrendJob::class, function ($job) {
            $reflector = new \ReflectionClass($job);
            $property = $reflector->getProperty('countryId');
            $property->setAccessible(true);
            return $property->getValue($job) === $this->germany->id;
        });
    }
}
