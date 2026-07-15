<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use App\Services\CountryService;
use App\Jobs\SyncCountryJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CountryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $country;
    protected $mockResponseData;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);

        $analystRole = Role::where('name', 'Analyst')->first();
        $this->user = User::factory()->create([
            'role_id' => $analystRole->id
        ]);

        // Create initial pilot country
        $this->country = Country::create([
            'code' => 'DE',
            'name' => 'Germany',
            'currency_code' => 'EUR',
            'currency_name' => 'Euro',
            'region' => 'Europe',
            'language' => 'German',
            'population' => 84000000,
        ]);

        // Define mock response data from REST Countries API format
        $this->mockResponseData = [
            [
                'name' => ['common' => 'Germany'],
                'cca2' => 'DE',
                'cca3' => 'DEU',
                'region' => 'Europe',
                'subregion' => 'Western Europe',
                'population' => 83240525,
                'area' => 357114.0,
                'currencies' => [
                    'EUR' => [
                        'name' => 'Euro',
                        'symbol' => '€'
                    ]
                ],
                'languages' => [
                    'deu' => 'German'
                ],
                'flags' => [
                    'png' => 'https://flagcdn.com/w320/de.png',
                    'svg' => 'https://flagcdn.com/de.svg'
                ],
                'capital' => ['Berlin'],
                'latlng' => [51.0, 9.0],
                'capitalInfo' => [
                    'latlng' => [52.52, 13.40]
                ],
                'timezones' => [
                    'UTC+01:00'
                ]
            ]
        ];
    }

    /**
     * Test CountryService fetches, parses, and syncs country data correctly.
     */
    public function test_service_can_sync_country_data_from_api(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/DE' => Http::response($this->mockResponseData, 200)
        ]);

        $service = app(CountryService::class);
        $country = $service->syncCountry('DE');

        $this->assertNotNull($country);
        $this->assertEquals('Germany', $country->name);
        $this->assertEquals('DE', $country->iso2);
        $this->assertEquals('DEU', $country->iso3);
        $this->assertEquals('https://flagcdn.com/w320/de.png', $country->flag_url);
        $this->assertEquals('EUR', $country->currency_code);
        $this->assertEquals('Euro', $country->currency_name);
        $this->assertEquals('€', $country->currency_symbol);
        $this->assertEquals('Western Europe', $country->subregion);
        $this->assertEquals('Berlin', $country->capital);
        $this->assertEquals('German', $country->language);
        $this->assertEquals(52.52, (float) $country->latitude);
        $this->assertEquals(13.40, (float) $country->longitude);
        $this->assertEquals(83240525, $country->population);
        $this->assertEquals(357114.0, (float) $country->area);
        $this->assertEquals('UTC+01:00', $country->timezone);

        // Verify activity logs contain request & audit log
        $this->assertDatabaseHas('activity_logs', [
            'log_type' => 'api_request',
            'description' => 'Panggilan REST Countries API untuk kode DE',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'log_type' => 'audit',
            'description' => "Berhasil menyelaraskan data negara 'Germany' dari REST Countries API.",
        ]);
    }

    /**
     * Test caching behavior in CountryService.
     */
    public function test_service_caches_api_response_for_24_hours(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/DE' => Http::response($this->mockResponseData, 200)
        ]);

        $service = app(CountryService::class);
        
        Cache::forget('rest_countries_service_DE');

        // First call should execute HTTP request
        $service->syncCountry('DE');
        Http::assertSentCount(1);

        // Second call should retrieve from cache
        $service->syncCountry('DE');
        Http::assertSentCount(1);
    }

    /**
     * Test validation throws exception when response is invalid.
     */
    public function test_service_throws_exception_on_invalid_api_response(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/DE' => Http::response([['name' => []]], 200) // missing cca2, cca3, name.common
        ]);

        $service = app(CountryService::class);

        $this->expectException(\RuntimeException::class);
        $service->syncCountry('DE', true);
    }

    /**
     * Test Queue Job dispatches and executes correctly.
     */
    public function test_queue_job_syncs_country(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/DE' => Http::response($this->mockResponseData, 200)
        ]);

        Queue::fake();

        SyncCountryJob::dispatch('DE', true);

        Queue::assertPushed(SyncCountryJob::class, function ($job) {
            return $job->countryCode === 'DE' && $job->forceRefresh === true;
        });
    }

    /**
     * Test Artisan command triggers sync.
     */
    public function test_artisan_command_syncs_countries(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/DE' => Http::response($this->mockResponseData, 200)
        ]);

        $this->artisan('countries:sync', ['country' => 'DE'])
            ->expectsOutput("Memulai sinkronisasi untuk negara: DE...")
            ->expectsOutput("Sukses! Data negara 'Germany' (DE) berhasil diperbarui.")
            ->assertExitCode(0);
    }

    /**
     * Test controller sync endpoint.
     */
    public function test_controller_sync_endpoint(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/DE' => Http::response($this->mockResponseData, 200)
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('countries.sync', ['code' => 'DE']));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => "Data lokal negara 'Germany' berhasil disinkronisasikan dengan REST Countries, World Bank, dan Open-Meteo API."
            ]);

        $this->assertDatabaseHas('countries', [
            'code' => 'DE',
            'iso3' => 'DEU',
            'capital' => 'Berlin'
        ]);
    }

    /**
     * Test controller sync all endpoint.
     */
    public function test_controller_sync_all_endpoint(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/DE' => Http::response($this->mockResponseData, 200)
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('countries.sync-all'));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => "Sinkronisasi seluruh negara selesai. Sukses: 1, Gagal: 0."
            ]);
    }

    /**
     * Test controller detail endpoint.
     */
    public function test_controller_detail_endpoint(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/alpha/DE' => Http::response($this->mockResponseData, 200),
            'http://api.worldbank.org/v2/country/*' => Http::response([
                ['page' => 1, 'pages' => 1, 'per_page' => 50, 'total' => 1],
                [['value' => null]]
            ], 200)
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('countries.detail', ['code' => 'DE']));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Germany',
                    'area' => 357114.0,
                    'subregion' => 'Western Europe',
                ]
            ]);
    }
}
