<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskSnapshot;
use App\Models\RiskClassification;
use App\Services\RiskAggregationService;
use App\Jobs\GenerateCountryRiskSnapshotJob;
use App\DTOs\RiskAggregationDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiskAggregationTest extends TestCase
{
    use RefreshDatabase;

    protected Country $country;

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

        $this->country->coordinates()->create([
            'latitude' => 51.165691,
            'longitude' => 10.451526,
        ]);
        
        RiskClassification::create([
            'name' => 'Medium',
            'min_score' => 40.01,
            'max_score' => 70.00,
            'color_code' => '#F59E0B'
        ]);
    }

    /** @test */
    public function test_it_aggregates_all_sources_and_saves_snapshot(): void
    {
        // Fake all HTTP clients
        Http::fake([
            'https://api.open-meteo.com/*' => Http::response([
                'latitude' => 51.165,
                'longitude' => 10.45,
                'current' => [
                    'temperature_2m' => 18.5,
                    'weather_code' => 3, // Cloudy
                    'relative_humidity_2m' => 60.0,
                    'wind_speed_10m' => 12.0,
                    'pressure_msl' => 1013.0,
                ]
            ], 200),
            'https://v6.exchangerate-api.com/*' => Http::response([
                'base' => 'USD',
                'rates' => [
                    'EUR' => 0.92,
                ]
            ], 200),
            'https://api.worldbank.org/*' => Http::response([
                [
                    'page' => 1,
                    'pages' => 1,
                    'per_page' => 50,
                    'total' => 1,
                ],
                [
                    [
                        'indicator' => ['id' => 'NY.GDP.MKTP.CD', 'value' => 'GDP'],
                        'country' => ['id' => 'DE', 'value' => 'Germany'],
                        'countryiso3code' => 'DEU',
                        'date' => '2023',
                        'value' => 4400000000000,
                    ]
                ]
            ], 200),
            'https://gnews.io/api/v4/*' => Http::response([
                'articles' => [
                    [
                        'title' => 'Supply chain disruptions in Germany',
                        'description' => 'German manufacturing suffers from port strikes.',
                        'url' => 'https://example.com/germany-strike',
                        'source' => ['name' => 'Deutsche Welle', 'url' => 'https://dw.com']
                    ]
                ]
            ], 200),
            'https://msi.nga.mil/api/*' => Http::response([
                'features' => [
                    [
                        'attributes' => [
                            'index_no' => '98765',
                            'port_name' => 'Hamburg',
                            'country' => 'Germany',
                            'harborsize' => 'L',
                            'harbortype' => 'RN',
                        ],
                        'geometry' => ['x' => 9.99, 'y' => 53.55]
                    ]
                ]
            ], 200),
        ]);

        $service = app(RiskAggregationService::class);
        $dto = $service->aggregateForCountry($this->country, true);

        $this->assertInstanceOf(RiskAggregationDTO::class, $dto);
        $this->assertEquals('Germany', $dto->countryName);
        $this->assertEquals('DE', $dto->isoCode);
        $this->assertEquals(18.5, $dto->weather['temperature']);
        $this->assertEquals(0.92, $dto->exchangeRate['rate']);
        $this->assertEquals(4400000000000, $dto->economic['gdp']);
        $this->assertCount(1, $dto->news);
        $this->assertCount(1, $dto->ports);

        // Verify snapshot database insertion
        $this->assertDatabaseHas('risk_snapshots', [
            'country_id' => $this->country->id,
            'overall_status' => 'Normal'
        ]);
    }

    /** @test */
    public function test_it_handles_failures_gracefully_with_fallbacks(): void
    {
        // Fake all HTTP clients to return error codes or bad structures
        Http::fake([
            '*' => Http::response([], 500)
        ]);

        $service = app(RiskAggregationService::class);
        $dto = $service->aggregateForCountry($this->country, true);

        // Process must still complete successfully
        $this->assertInstanceOf(RiskAggregationDTO::class, $dto);
        $this->assertNull($dto->weather['temperature']);
        $this->assertEquals('Unknown', $dto->weather['condition']);
        $this->assertEquals(1.0, $dto->exchangeRate['rate']); // Default rate
        $this->assertNull($dto->economic['gdp']);
        $this->assertEmpty($dto->news);
        $this->assertEmpty($dto->ports);

        $this->assertDatabaseHas('risk_snapshots', [
            'country_id' => $this->country->id,
            'overall_status' => 'Normal'
        ]);
    }

    /** @test */
    public function test_job_dispatches_properly_for_single_and_bulk(): void
    {
        Queue::fake();

        // Single country job
        GenerateCountryRiskSnapshotJob::dispatch($this->country->id);
        Queue::assertPushed(GenerateCountryRiskSnapshotJob::class);

        // Bulk country job
        GenerateCountryRiskSnapshotJob::dispatch();
        Queue::assertPushed(GenerateCountryRiskSnapshotJob::class);
    }
}
