<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskTrend;
use App\DTOs\RiskTrendDTO;
use App\Repositories\Interfaces\RiskTrendRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiskTrendRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected Country $indonesia;
    protected RiskTrendRepositoryInterface $repository;

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

        $this->repository = app(RiskTrendRepositoryInterface::class);
    }

    /** @test */
    public function test_it_can_save_and_retrieve_trends_for_country(): void
    {
        $dto = new RiskTrendDTO([
            'countryName' => 'Indonesia',
            'currentScore' => 45.0,
            'previousScore' => 40.0,
            'scoreDifference' => 5.0,
            'percentageChange' => 12.5,
            'previousRank' => 3,
            'currentRank' => 2,
            'rankDifference' => 1,
            'classificationDifference' => 0,
            'trendDirection' => 'Up',
            'trendStrength' => 'Moderate',
            'analysisTime' => now()->toIso8601String(),
        ]);

        $savedTrend = $this->repository->saveTrend($this->indonesia->id, $dto);

        $this->assertInstanceOf(RiskTrend::class, $savedTrend);
        $this->assertEquals($this->indonesia->id, $savedTrend->country_id);
        $this->assertEquals(45.0, $savedTrend->current_score);
        $this->assertEquals(40.0, $savedTrend->previous_score);
        $this->assertEquals(12.5, $savedTrend->change_percentage);

        // Fetch trends back
        $trends = $this->repository->getForCountry($this->indonesia->id);
        $this->assertCount(1, $trends);
        $this->assertEquals('overall', $trends->first()->trend_type);
    }
}
