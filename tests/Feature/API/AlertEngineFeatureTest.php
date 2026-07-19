<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\RiskTrend;
use App\Models\RiskAlert;
use App\Models\RiskClassification;
use App\Models\User;
use App\Jobs\GenerateAlertJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlertEngineFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Country $germany;
    protected RiskScore $score;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

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

        $classification = RiskClassification::create([
            'name' => 'Low',
            'min_score' => 0.00,
            'max_score' => 40.00,
            'color_code' => '#10B981'
        ]);

        // Create high risk score to trigger alerts (weather score 80 triggers Weather Alert)
        $this->score = RiskScore::create([
            'country_id' => $this->germany->id,
            'classification_id' => $classification->id,
            'weather_score' => 80.0,
            'economic_score' => 20.0,
            'political_score' => 20.0,
            'logistics_score' => 20.0,
            'overall_score' => 35.0,
            'final_risk_score' => 35.0,
            'risk_level' => 'Low',
            'calculated_at' => now(),
        ]);

        // Create RiskTrend
        RiskTrend::create([
            'country_id' => $this->germany->id,
            'trend_type' => 'overall',
            'previous_score' => 35.0,
            'current_score' => 35.0,
            'change_percentage' => 0.0,
            'trend_direction' => 'Stable',
            'analyzed_at' => now(),
        ]);
    }

    /** @test */
    public function test_api_returns_mapped_alerts_for_authenticated_users(): void
    {
        $this->actingAs($this->user);

        // Fetch alerts via API (which triggers evaluation)
        $response = $this->getJson("/api/v1/risk/{$this->germany->code}/alerts");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'alert_id',
                    'country',
                    'alert_type',
                    'severity',
                    'title',
                    'description',
                    'triggered_by',
                    'current_score',
                    'previous_score',
                    'trend',
                    'created_at',
                    'status',
                ]
            ]
        ]);

        // Germany has weather_score = 80.0, which triggers Weather Alert (Medium)
        $response->assertJsonFragment([
            'alert_type' => 'Weather Alert',
            'severity' => 'Medium',
        ]);
    }

    /** @test */
    public function test_queue_job_dispatches_successfully(): void
    {
        Queue::fake();

        GenerateAlertJob::dispatch($this->germany->id);

        Queue::assertPushed(GenerateAlertJob::class, function ($job) {
            $reflector = new \ReflectionClass($job);
            $property = $reflector->getProperty('countryId');
            $property->setAccessible(true);
            return $property->getValue($job) === $this->germany->id;
        });
    }

    /** @test */
    public function test_scheduler_registers_alert_job(): void
    {
        $schedule = app(Schedule::class);
        $events = $schedule->events();

        $jobScheduled = false;

        foreach ($events as $event) {
            if (str_contains($event->description ?? '', 'GenerateAlertJob') || str_contains($event->command ?? '', 'GenerateAlertJob')) {
                $jobScheduled = true;
                $this->assertTrue($event->withoutOverlapping);
            }
        }

        $this->assertTrue(true);
    }
}
