<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\RiskAlert;
use App\Models\RiskTrend;
use App\Models\RiskClassification;
use App\Repositories\Interfaces\DashboardAnalyticsRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardAnalyticsRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected Country $germany;
    protected Country $france;
    protected RiskClassification $lowClassification;
    protected RiskClassification $highClassification;
    protected DashboardAnalyticsRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $europe = Region::create(['name' => 'Europe']);
        $usd = Currency::create(['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$']);

        $this->germany = Country::create([
            'name' => 'Germany',
            'code' => 'DE',
            'region_id' => $europe->id,
            'currency_id' => $usd->id,
            'subregion' => 'Western Europe',
            'population' => 83000000,
        ]);

        $this->france = Country::create([
            'name' => 'France',
            'code' => 'FR',
            'region_id' => $europe->id,
            'currency_id' => $usd->id,
            'subregion' => 'Western Europe',
            'population' => 67000000,
        ]);

        $this->lowClassification = RiskClassification::create([
            'name' => 'Low',
            'min_score' => 0.00,
            'max_score' => 40.00,
            'color_code' => '#10B981',
        ]);

        $this->highClassification = RiskClassification::create([
            'name' => 'High',
            'min_score' => 70.00,
            'max_score' => 100.00,
            'color_code' => '#EF4444',
        ]);

        // Germany: Low Risk
        RiskScore::create([
            'country_id' => $this->germany->id,
            'classification_id' => $this->lowClassification->id,
            'weather_score' => 10.0,
            'economic_score' => 10.0,
            'political_score' => 10.0,
            'logistics_score' => 10.0,
            'overall_score' => 10.0,
            'final_risk_score' => 10.0,
            'risk_level' => 'Low',
            'calculated_at' => now(),
        ]);

        // France: High Risk
        RiskScore::create([
            'country_id' => $this->france->id,
            'classification_id' => $this->highClassification->id,
            'weather_score' => 80.0,
            'economic_score' => 80.0,
            'political_score' => 80.0,
            'logistics_score' => 80.0,
            'overall_score' => 80.0,
            'final_risk_score' => 80.0,
            'risk_level' => 'High',
            'calculated_at' => now(),
        ]);

        // Mock alerts
        RiskAlert::create([
            'country_id' => $this->france->id,
            'risk_score_id' => 2,
            'alert_type' => 'Weather Alert',
            'severity' => 'Critical',
            'title' => 'Critical Storm France',
            'status' => 'New',
        ]);

        // Mock trends
        RiskTrend::create([
            'country_id' => $this->france->id,
            'trend_type' => 'overall',
            'previous_score' => 60.0,
            'current_score' => 80.0,
            'change_percentage' => 33.33,
            'trend_direction' => 'Up',
            'analyzed_at' => now(),
        ]);

        $this->repository = app(DashboardAnalyticsRepositoryInterface::class);
    }

    /** @test */
    public function test_it_retrieves_overview_data_correctly(): void
    {
        $overview = $this->repository->getOverviewData([]);

        $this->assertEquals(2, $overview['total_countries']);
        $this->assertEquals(1, $overview['total_active_alerts']);
        $this->assertEquals(45.0, $overview['avg_risk_score']); // (10 + 80)/2
        $this->assertEquals('France', $overview['highest_risk_country']['name']);
        $this->assertEquals('Germany', $overview['lowest_risk_country']['name']);
    }

    /** @test */
    public function test_it_filters_overview_data_by_country(): void
    {
        $overview = $this->repository->getOverviewData(['country_id' => $this->germany->id]);

        $this->assertEquals(1, $overview['total_countries']);
        $this->assertEquals(0, $overview['total_active_alerts']);
        $this->assertEquals(10.0, $overview['avg_risk_score']);
    }

    /** @test */
    public function test_it_retrieves_risk_distribution_correctly(): void
    {
        $dist = $this->repository->getRiskDistributionData([]);

        $this->assertEquals(1, $dist['Low']);
        $this->assertEquals(1, $dist['High']);
    }

    /** @test */
    public function test_it_retrieves_component_risk_data_correctly(): void
    {
        $comp = $this->repository->getComponentRiskData('weather', []);

        $this->assertCount(2, $comp['scores']);
        $this->assertEquals(80.0, $comp['highest']['score']);
        $this->assertEquals('France', $comp['highest']['name']);
    }
}
