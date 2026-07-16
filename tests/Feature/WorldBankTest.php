<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use App\Services\WorldBankService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WorldBankTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $country;

    protected $mockGdpData;

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
            'currency_code' => 'EUR',
            'currency_name' => 'Euro',
            'region' => 'Europe',
            'language' => 'German',
            'gdp' => 4000000000000,
            'inflation' => 2.1,
            'population' => 83000000,
        ]);

        // Mock GDP response from api.worldbank.org
        $this->mockGdpData = [
            [
                'page' => 1,
                'pages' => 1,
                'per_page' => 50,
                'total' => 6,
            ],
            [
                [
                    'indicator' => ['id' => 'NY.GDP.MKTP.CD', 'value' => 'GDP (current USD)'],
                    'country' => ['id' => 'DE', 'value' => 'Germany'],
                    'countryiso3code' => 'DEU',
                    'date' => '2023',
                    'value' => 4456189000000,
                    'unit' => '',
                    'obs_status' => '',
                    'decimal' => 0,
                ],
                [
                    'indicator' => ['id' => 'NY.GDP.MKTP.CD', 'value' => 'GDP (current USD)'],
                    'country' => ['id' => 'DE', 'value' => 'Germany'],
                    'countryiso3code' => 'DEU',
                    'date' => '2022',
                    'value' => 4082468000000,
                    'unit' => '',
                    'obs_status' => '',
                    'decimal' => 0,
                ],
            ],
        ];
    }

    /**
     * Test WorldBankService fetchIndicator method.
     */
    public function test_service_can_fetch_and_parse_world_bank_data(): void
    {
        Http::fake([
            'http://api.worldbank.org/v2/country/de/indicator/NY.GDP.MKTP.CD*' => Http::response($this->mockGdpData, 200),
        ]);

        $service = app(WorldBankService::class);
        $result = $service->fetchIndicator('DE', 'gdp');

        $this->assertNotNull($result);
        $this->assertEquals(2023, $result['year']);
        $this->assertEquals(4456189000000, $result['value']);

        // Check activity logs
        $this->assertDatabaseHas('activity_logs', [
            'log_type' => 'api_request',
            'description' => 'Panggilan World Bank API untuk endpoint /v2/country/de/indicator/NY.GDP.MKTP.CD',
        ]);
    }

    /**
     * Test caching behavior in service.
     */
    public function test_service_caches_economic_indicators(): void
    {
        Http::fake([
            'http://api.worldbank.org/v2/country/de/indicator/NY.GDP.MKTP.CD*' => Http::response($this->mockGdpData, 200),
        ]);

        $service = app(WorldBankService::class);
        Cache::forget('world_bank_de_gdp');

        // First call
        $service->fetchIndicator('DE', 'gdp');
        Http::assertSentCount(1);

        // Second call should hit cache
        $service->fetchIndicator('DE', 'gdp');
        Http::assertSentCount(1);
    }

    /**
     * Test controller economic sync updates database.
     */
    public function test_controller_syncs_world_bank_metrics_into_local_database(): void
    {
        // Mock all 5 indicators to return valid metrics
        Http::fake([
            'http://api.worldbank.org/v2/country/de/indicator/NY.GDP.MKTP.CD*' => Http::response($this->mockGdpData, 200), // GDP
            'http://api.worldbank.org/v2/country/de/indicator/FP.CPI.TOTL.ZG*' => Http::response([[], [['date' => '2023', 'value' => 3.5]]], 200), // Inflation
            'http://api.worldbank.org/v2/country/de/indicator/SP.POP.TOTL*' => Http::response([[], [['date' => '2023', 'value' => 84100000]]], 200), // Population
            'http://api.worldbank.org/v2/country/de/indicator/NE.EXP.GNFS.CD*' => Http::response([[], [['date' => '2023', 'value' => 2000000000000]]], 200), // Export
            'http://api.worldbank.org/v2/country/de/indicator/NE.IMP.GNFS.CD*' => Http::response([[], [['date' => '2023', 'value' => 1800000000000]]], 200), // Import
            'https://restcountries.com/v3.1/alpha/DE*' => Http::response([], 404), // Mock Rest Countries to fail gracefully
        ]);

        $this->assertEquals(4000000000000, $this->country->gdp);

        $response = $this->actingAs($this->user)
            ->postJson(route('countries.sync', ['code' => 'DE']));

        $response->assertStatus(200);

        $this->country->refresh();
        $this->assertEquals(4456189000000, $this->country->gdp);
        $this->assertEquals(3.5, $this->country->inflation);
        $this->assertEquals(84100000, $this->country->population);
        $this->assertEquals(2000000000000, $this->country->export_value);
        $this->assertEquals(1800000000000, $this->country->import_value);
    }
}
