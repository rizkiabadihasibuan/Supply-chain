<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use App\Services\RiskClassificationService;
use App\Jobs\ClassifyRiskJob;
use App\DTOs\RiskClassificationDTO;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiskClassificationTest extends TestCase
{
    use RefreshDatabase;

    protected Country $country;
    protected RiskScore $score;

    protected function setUp(): void
    {
        parent::setUp();

        $region = Region::create(['name' => 'Europe']);
        $currency = Currency::create(['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€']);
        
        $this->country = Country::create([
            'name' => 'Germany',
            'code' => 'DE',
            'region_id' => $region->id,
            'currency_id' => $currency->id,
            'population' => 83000000,
            'timezone' => 'Europe/Berlin',
        ]);

        // Seed classifications
        RiskClassification::create(['name' => 'Very Low', 'min_score' => 0.00, 'max_score' => 20.00, 'color_code' => '#10B981']);
        RiskClassification::create(['name' => 'Low', 'min_score' => 20.01, 'max_score' => 40.00, 'color_code' => '#84CC16']);
        RiskClassification::create(['name' => 'Medium', 'min_score' => 40.01, 'max_score' => 60.00, 'color_code' => '#F59E0B']);
        RiskClassification::create(['name' => 'High', 'min_score' => 60.01, 'max_score' => 80.00, 'color_code' => '#EF4444']);
        RiskClassification::create(['name' => 'Critical', 'min_score' => 80.01, 'max_score' => 100.00, 'color_code' => '#7F1D1D']);

        // Medium risk score
        $this->score = RiskScore::create([
            'country_id' => $this->country->id,
            'classification_id' => 1, // temporary ID
            'weather_score' => 50.0,
            'economic_score' => 50.0,
            'political_score' => 50.0,
            'logistics_score' => 50.0,
            'overall_score' => 50.0,
            'final_risk_score' => 50.0,
            'risk_level' => 'Very Low',
            'calculated_at' => now(),
            'source_version' => '1.0.0',
        ]);
    }

    /** @test */
    public function test_it_classifies_country_risk_and_updates_database(): void
    {
        $service = app(RiskClassificationService::class);
        $dto = $service->classifyForCountry($this->country, true);

        $this->assertInstanceOf(RiskClassificationDTO::class, $dto);
        $this->assertEquals('Germany', $dto->countryName);
        $this->assertEquals(50.0, $dto->overallScore);
        $this->assertEquals('Medium', $dto->riskLevel);
        $this->assertEquals('#F59E0B', $dto->riskColor);
        $this->assertEquals('Attention', $dto->priority);
        $this->assertEquals('Attention', $dto->recommendationLevel);

        // Verify database is updated
        $this->assertDatabaseHas('risk_scores', [
            'id' => $this->score->id,
            'risk_level' => 'Medium',
        ]);
    }

    /** @test */
    public function test_it_throws_exception_for_out_of_bounds_score(): void
    {
        $this->score->update(['overall_score' => 150.00]);

        $this->expectException(\InvalidArgumentException::class);
        $service = app(RiskClassificationService::class);
        $service->classifyForCountry($this->country, true);
    }

    /** @test */
    public function test_job_dispatches_properly_for_single_and_bulk(): void
    {
        Queue::fake();

        // Single country job
        ClassifyRiskJob::dispatch($this->country->id);
        Queue::assertPushed(ClassifyRiskJob::class);

        // Bulk country job
        ClassifyRiskJob::dispatch();
        Queue::assertPushed(ClassifyRiskJob::class);
    }
}
