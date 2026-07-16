<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use App\Services\CurrencyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CurrencyServiceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $country;

    protected $mockRatesData;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);

        $analystRole = Role::where('name', 'Analyst')->first();
        $this->user = User::factory()->create([
            'role_id' => $analystRole->id,
        ]);

        // Create country (Germany - EUR)
        $this->country = Country::create([
            'code' => 'DE',
            'name' => 'Germany',
            'iso2' => 'DE',
            'iso3' => 'DEU',
            'currency_code' => 'EUR',
            'currency_name' => 'Euro',
            'currency_symbol' => '€',
        ]);

        $this->mockRatesData = [
            'base_code' => 'USD',
            'rates' => [
                'EUR' => 0.92,
                'IDR' => 16200.0,
                'GBP' => 0.78,
            ],
        ];
    }

    /**
     * Test CurrencyService can sync currency exchange rate and maintain history.
     */
    public function test_service_can_sync_currency_rates(): void
    {
        Http::fake([
            'https://open.er-api.com/v6/latest/USD' => Http::response($this->mockRatesData, 200),
        ]);

        $service = app(CurrencyService::class);
        $country = $service->syncCountryCurrency('DE', true);

        $this->assertNotNull($country);
        $this->assertEquals(0.92, $country->exchange_rate);
        $this->assertEquals('USD', $country->exchange_rate_base);
        $this->assertIsArray($country->exchange_rate_history);
        $this->assertCount(1, $country->exchange_rate_history);
        $this->assertEquals(now()->format('Y-m-d'), $country->exchange_rate_history[0]['date']);
        $this->assertEquals(0.92, $country->exchange_rate_history[0]['rate']);

        // Verify audit log exists
        $this->assertDatabaseHas('activity_logs', [
            'log_type' => 'audit',
            'description' => "Berhasil menyelaraskan data kurs mata uang 'EUR' negara 'Germany' dari Exchange Rate API.",
        ]);
    }

    /**
     * Test caching behavior: second call should not trigger HTTP requests.
     */
    public function test_service_caches_rates(): void
    {
        Http::fake([
            'https://open.er-api.com/v6/latest/USD' => Http::response($this->mockRatesData, 200),
        ]);

        $service = app(CurrencyService::class);
        Cache::forget('exchange_rates_usd_base');

        // First call
        $service->syncCountryCurrency('DE', false);
        Http::assertSentCount(1);

        // Second call (hits cache)
        $service->syncCountryCurrency('DE', false);
        Http::assertSentCount(1); // should still be 1
    }

    /**
     * Test syncAllCurrencies method.
     */
    public function test_service_sync_all_method(): void
    {
        Http::fake([
            'https://open.er-api.com/v6/latest/USD' => Http::response($this->mockRatesData, 200),
        ]);

        $service = app(CurrencyService::class);
        $results = $service->syncAllCurrencies(true);

        $this->assertContains('DE', $results['success']);
        $this->assertEmpty($results['failed']);

        $this->assertDatabaseHas('countries', [
            'code' => 'DE',
            'exchange_rate' => 0.92,
        ]);
    }

    /**
     * Test artisan command syncs currency data.
     */
    public function test_artisan_command_syncs_currency(): void
    {
        Http::fake([
            'https://open.er-api.com/v6/latest/USD' => Http::response($this->mockRatesData, 200),
        ]);

        $exitCode = Artisan::call('currency:sync', [
            'country' => 'DE',
            '--force' => true,
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertDatabaseHas('countries', [
            'code' => 'DE',
            'exchange_rate' => 0.92,
        ]);
    }

    /**
     * Test controller sync endpoints.
     */
    public function test_controller_sync_endpoints(): void
    {
        Http::fake([
            'https://open.er-api.com/v6/latest/USD' => Http::response($this->mockRatesData, 200),
        ]);

        // Single sync endpoint
        $response = $this->actingAs($this->user)
            ->postJson(route('countries.sync-currency', ['code' => 'DE']));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => "Data kurs negara 'Germany' (EUR) berhasil diperbarui dari Exchange Rate API.",
            ]);

        // Mass sync endpoint
        $responseAll = $this->actingAs($this->user)
            ->postJson(route('countries.sync-all-currency'));

        $responseAll->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Sinkronisasi kurs seluruh negara selesai. Sukses: 1, Gagal: 0.',
            ]);
    }
}
