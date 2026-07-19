<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Country;
use App\Models\RiskSnapshot;
use App\Models\RiskScore;
use App\Models\RiskHistory;
use App\Models\RiskAlert;
use App\Models\RiskTrend;
use App\Repositories\Interfaces\RiskSnapshotRepositoryInterface;
use App\Repositories\Interfaces\RiskAlertRepositoryInterface;
use App\Repositories\Interfaces\RiskTrendRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiskDatabaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed classifications since RiskScoreFactory depends on it
        $classifications = [
            ['name' => 'Low', 'min_score' => 0.00, 'max_score' => 40.00, 'color_code' => '#10B981'],
            ['name' => 'Medium', 'min_score' => 40.01, 'max_score' => 70.00, 'color_code' => '#F59E0B'],
            ['name' => 'High', 'min_score' => 70.01, 'max_score' => 100.00, 'color_code' => '#EF4444'],
        ];
        foreach ($classifications as $c) {
            \App\Models\RiskClassification::create($c);
        }
    }

    /** @test */
    public function test_factories_can_create_entities_with_proper_relations(): void
    {
        $snapshot = RiskSnapshot::factory()->create();
        $this->assertInstanceOf(Country::class, $snapshot->country);

        $score = RiskScore::factory()->create(['snapshot_id' => $snapshot->id]);
        $this->assertInstanceOf(RiskSnapshot::class, $score->snapshot);
        $this->assertEquals($snapshot->id, $score->snapshot_id);

        $history = RiskHistory::factory()->create(['risk_score_id' => $score->id]);
        $this->assertInstanceOf(RiskScore::class, $history->riskScore);

        $alert = RiskAlert::factory()->create(['risk_score_id' => $score->id]);
        $this->assertInstanceOf(RiskScore::class, $alert->riskScore);

        $trend = RiskTrend::factory()->create();
        $this->assertInstanceOf(Country::class, $trend->country);
    }

    /** @test */
    public function test_repositories_can_retrieve_entities(): void
    {
        $country = Country::factory()->create();
        
        $snapshotRepo = app(RiskSnapshotRepositoryInterface::class);
        $alertRepo = app(RiskAlertRepositoryInterface::class);
        $trendRepo = app(RiskTrendRepositoryInterface::class);

        // Seed snapshot
        $snapshot = RiskSnapshot::factory()->create([
            'country_id' => $country->id,
            'snapshot_time' => now()
        ]);
        
        $latestSnapshot = $snapshotRepo->getLatestForCountry($country->id);
        $this->assertEquals($snapshot->id, $latestSnapshot->id);

        // Seed alert
        $score = RiskScore::factory()->create(['country_id' => $country->id, 'snapshot_id' => $snapshot->id]);
        $alert = RiskAlert::factory()->create([
            'country_id' => $country->id,
            'risk_score_id' => $score->id,
            'status' => 'Active'
        ]);

        $activeAlerts = $alertRepo->getActiveForCountry($country->id);
        $this->assertCount(1, $activeAlerts);
        $this->assertEquals($alert->id, $activeAlerts->first()->id);

        // Seed trend
        $trend = RiskTrend::factory()->create([
            'country_id' => $country->id,
            'analyzed_at' => now()
        ]);

        $trends = $trendRepo->getForCountry($country->id);
        $this->assertCount(1, $trends);
        $this->assertEquals($trend->id, $trends->first()->id);
    }
}
