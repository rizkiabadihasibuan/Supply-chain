<?php

namespace Tests\Feature;

use App\Jobs\SyncEconomicDataJob;
use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use App\Services\WorldBankService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class WorldBankServiceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $country;

    protected $mockGdpData;

    protected $mockInflationData;

    protected $mockPopulationData;

    protected $mockExportData;

    protected $mockImportData;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);

        $analystRole = Role::where('name', 'Analyst')->first();
        $this->user = User::factory()->create([
            'role_id' => $analystRole->id,
        ]);

        // Create country
        $this->country = Country::create([
            'code' => 'DE',
            'name' => 'Germany',
            'iso2' => 'DE',
            'iso3' => 'DEU',
            'gdp' => 4000000000000,
            'inflation' => 2.1,
            'population' => 83000000,
            'export_value' => 1500000000000,
            'import_value' => 1300000000000,
        ]);

        // World Bank Mock responses
        $this->mockGdpData = [[], [['date' => '2023', 'value' => 4456189000000]]];
        $this->mockInflationData = [[], [['date' => '2023', 'value' => 3.5]]];
        $this->mockPopulationData = [[], [['date' => '2023', 'value' => 84100000]]];
        $this->mockExportData = [[], [['date' => '2023', 'value' => 2000000000000]]];
        $this->mockImportData = [[], [['date' => '2023', 'value' => 1800000000000]]];
    }

    /**
     * Test WorldBankService can sync economic indicators into database.
     */
    public function test_service_can_sync_economic_data_into_local_database(): void
    {
        Http::fake([
            'http://api.worldbank.org/v2/country/de/indicator/NY.GDP.MKTP.CD*' => Http::response($this->mockGdpData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/FP.CPI.TOTL.ZG*' => Http::response($this->mockInflationData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/SP.POP.TOTL*' => Http::response($this->mockPopulationData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/NE.EXP.GNFS.CD*' => Http::response($this->mockExportData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/NE.IMP.GNFS.CD*' => Http::response($this->mockImportData, 200),
        ]);

        $service = app(WorldBankService::class);
        $country = $service->syncCountryEconomicData('DE', true);

        $this->assertNotNull($country);
        $this->assertEquals(4456189000000, $country->gdp);
        $this->assertEquals(3.5, $country->inflation);
        $this->assertEquals(84100000, $country->population);
        $this->assertEquals(2000000000000, $country->export_value);
        $this->assertEquals(1800000000000, $country->import_value);

        // Verify audit log exists
        $this->assertDatabaseHas('activity_logs', [
            'log_type' => 'audit',
            'description' => "Berhasil menyelaraskan data ekonomi negara 'Germany' dari World Bank API.",
        ]);
    }

    /**
     * Test caching behavior: second call should not trigger HTTP requests.
     */
    public function test_service_caches_economic_responses(): void
    {
        Http::fake([
            'http://api.worldbank.org/v2/country/de/indicator/NY.GDP.MKTP.CD*' => Http::response($this->mockGdpData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/FP.CPI.TOTL.ZG*' => Http::response($this->mockInflationData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/SP.POP.TOTL*' => Http::response($this->mockPopulationData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/NE.EXP.GNFS.CD*' => Http::response($this->mockExportData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/NE.IMP.GNFS.CD*' => Http::response($this->mockImportData, 200),
        ]);

        $service = app(WorldBankService::class);

        // Clear caches
        Cache::forget('world_bank_de_gdp');
        Cache::forget('world_bank_de_inflation');
        Cache::forget('world_bank_de_population');
        Cache::forget('world_bank_de_export');
        Cache::forget('world_bank_de_import');

        // First call
        $service->syncCountryEconomicData('DE', false);
        Http::assertSentCount(5);

        // Second call (hits cache)
        $service->syncCountryEconomicData('DE', false);
        Http::assertSentCount(5); // should still be 5
    }

    /**
     * Test exception is thrown when World Bank API returns null for all indicators.
     */
    public function test_service_throws_exception_on_invalid_or_null_metrics(): void
    {
        Http::fake([
            'http://api.worldbank.org/v2/country/de/indicator/*' => Http::response([[], []], 200),
        ]);

        $service = app(WorldBankService::class);

        $this->expectException(\RuntimeException::class);
        $service->syncCountryEconomicData('DE', true);
    }

    /**
     * Test artisan command syncs data.
     */
    public function test_artisan_command_syncs_economic_data(): void
    {
        Http::fake([
            'http://api.worldbank.org/v2/country/de/indicator/NY.GDP.MKTP.CD*' => Http::response($this->mockGdpData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/FP.CPI.TOTL.ZG*' => Http::response($this->mockInflationData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/SP.POP.TOTL*' => Http::response($this->mockPopulationData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/NE.EXP.GNFS.CD*' => Http::response($this->mockExportData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/NE.IMP.GNFS.CD*' => Http::response($this->mockImportData, 200),
        ]);

        $exitCode = Artisan::call('economic:sync', [
            'country' => 'DE',
            '--force' => true,
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertDatabaseHas('countries', [
            'code' => 'DE',
            'gdp' => 4456189000000,
        ]);
    }

    /**
     * Test queue option dispatches job.
     */
    public function test_queue_job_dispatches_economic_sync(): void
    {
        Queue::fake();

        $exitCode = Artisan::call('economic:sync', [
            'country' => 'DE',
            '--queue' => true,
        ]);

        $this->assertEquals(0, $exitCode);
        Queue::assertPushed(SyncEconomicDataJob::class, function ($job) {
            return $job->countryCode === 'DE';
        });
    }

    /**
     * Test controller single and mass economic sync endpoints.
     */
    public function test_controller_sync_endpoints(): void
    {
        Http::fake([
            'http://api.worldbank.org/v2/country/de/indicator/NY.GDP.MKTP.CD*' => Http::response($this->mockGdpData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/FP.CPI.TOTL.ZG*' => Http::response($this->mockInflationData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/SP.POP.TOTL*' => Http::response($this->mockPopulationData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/NE.EXP.GNFS.CD*' => Http::response($this->mockExportData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/NE.IMP.GNFS.CD*' => Http::response($this->mockImportData, 200),
        ]);

        // Single sync endpoint
        $response = $this->actingAs($this->user)
            ->postJson(route('countries.sync-economic', ['code' => 'DE']));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => "Data ekonomi negara 'Germany' berhasil disinkronisasikan dengan World Bank API.",
            ]);

        // Mass sync endpoint
        $responseAll = $this->actingAs($this->user)
            ->postJson(route('countries.sync-all-economic'));

        $responseAll->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Sinkronisasi data ekonomi seluruh negara selesai. Sukses: 1, Gagal: 0.',
            ]);
    }
}
