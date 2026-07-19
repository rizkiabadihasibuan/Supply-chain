<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\RiskAlert;
use App\Models\RiskClassification;
use App\Repositories\Interfaces\RiskAlertRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiskAlertRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected Country $indonesia;
    protected RiskScore $score;
    protected RiskAlertRepositoryInterface $repository;

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

        $classification = RiskClassification::create([
            'name' => 'Low',
            'min_score' => 0.00,
            'max_score' => 40.00,
            'color_code' => '#10B981'
        ]);

        $this->score = RiskScore::create([
            'country_id' => $this->indonesia->id,
            'classification_id' => $classification->id,
            'weather_score' => 10.0,
            'economic_score' => 10.0,
            'political_score' => 10.0,
            'logistics_score' => 10.0,
            'overall_score' => 10.0,
            'final_risk_score' => 10.0,
            'risk_level' => 'Low',
            'calculated_at' => now(),
        ]);

        $this->repository = app(RiskAlertRepositoryInterface::class);
    }

    /** @test */
    public function test_it_can_create_and_retrieve_active_alerts(): void
    {
        // 1. Create alerts in DB
        $alert1 = $this->repository->create([
            'country_id' => $this->indonesia->id,
            'risk_score_id' => $this->score->id,
            'alert_type' => 'Overall Risk Alert',
            'severity' => 'High',
            'title' => 'High Risk Triggered',
            'description' => 'Test description',
            'status' => 'New',
        ]);

        $alert2 = $this->repository->create([
            'country_id' => $this->indonesia->id,
            'risk_score_id' => $this->score->id,
            'alert_type' => 'Weather Alert',
            'severity' => 'Medium',
            'title' => 'Storm Warning',
            'status' => 'Resolved', // this is resolved
        ]);

        // 2. Fetch active alerts
        $activeAlerts = $this->repository->getActiveForCountry($this->indonesia->id);

        $this->assertCount(1, $activeAlerts);
        $this->assertEquals('Overall Risk Alert', $activeAlerts->first()->alert_type);
        $this->assertEquals('High', $activeAlerts->first()->severity);
    }
}
