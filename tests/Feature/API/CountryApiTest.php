<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Services\CountryService;
use App\Jobs\SyncCountriesJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard user & admin roles / records if needed.
        // For testing we will just bypass auth with actingAs or mock it.
        $this->user = User::factory()->create();
        
        $this->admin = User::factory()->create();
        // Grant admin role
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin']);
        $this->admin->roles()->attach($adminRole);
    }

    /** @test */
    public function test_it_can_sync_countries_from_api_and_store_in_db(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([
                [
                    'name' => [
                        'common' => 'Indonesia',
                        'official' => 'Republic of Indonesia'
                    ],
                    'cca2' => 'ID',
                    'cca3' => 'IDN',
                    'region' => 'Asia',
                    'subregion' => 'South-Eastern Asia',
                    'capital' => ['Jakarta'],
                    'population' => 273523621,
                    'area' => 1904569.0,
                    'latlng' => [-5.0, 120.0],
                    'timezones' => ['UTC+07:00'],
                    'currencies' => [
                        'IDR' => [
                            'name' => 'Indonesian rupiah',
                            'symbol' => 'Rp'
                        ]
                    ],
                    'languages' => [
                        'ind' => 'Indonesian'
                    ],
                    'flags' => [
                        'png' => 'https://flagcdn.com/w320/id.png',
                        'svg' => 'https://flagcdn.com/id.svg'
                    ]
                ]
            ], 200)
        ]);

        $service = app(CountryService::class);
        $result = $service->syncCountries(true);

        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['count']);

        // Assert database records
        $this->assertDatabaseHas('regions', ['name' => 'Asia']);
        $this->assertDatabaseHas('currencies', ['code' => 'IDR', 'name' => 'Indonesian rupiah']);
        $this->assertDatabaseHas('countries', [
            'code' => 'ID',
            'name' => 'Indonesia',
            'subregion' => 'South-Eastern Asia',
            'population' => 273523621,
            'area' => 1904569.0,
            'timezone' => 'UTC+07:00'
        ]);

        $country = Country::where('code', 'ID')->first();
        $this->assertNotNull($country);
        $this->assertDatabaseHas('country_coordinates', [
            'country_id' => $country->id,
            'latitude' => -5.0,
            'longitude' => 120.0
        ]);
        $this->assertDatabaseHas('country_flags', [
            'country_id' => $country->id,
            'flag_url' => 'https://flagcdn.com/w320/id.png',
            'svg_path' => 'https://flagcdn.com/id.svg'
        ]);
    }

    /** @test */
    public function test_it_can_fetch_countries_via_api_endpoints(): void
    {
        // Mock the REST Countries API response
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([
                [
                    'name' => [
                        'common' => 'France',
                        'official' => 'French Republic'
                    ],
                    'cca2' => 'FR',
                    'cca3' => 'FRA',
                    'region' => 'Europe',
                    'subregion' => 'Western Europe',
                    'capital' => ['Paris'],
                    'population' => 67000000,
                    'area' => 643801.0,
                    'latlng' => [46.0, 2.0],
                    'timezones' => ['UTC+01:00'],
                    'currencies' => [
                        'EUR' => [
                            'name' => 'Euro',
                            'symbol' => '€'
                        ]
                    ],
                    'languages' => [
                        'fra' => 'French'
                    ],
                    'flags' => [
                        'png' => 'https://flagcdn.com/w320/fr.png',
                        'svg' => 'https://flagcdn.com/fr.svg'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/countries');

        $response->assertStatus(200)
            ->assertJsonFragment(['country' => 'France', 'iso2' => 'FR']);
    }

    /** @test */
    public function test_it_can_search_countries_via_endpoint(): void
    {
        // Mock the REST Countries API response
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([
                [
                    'name' => [
                        'common' => 'United States',
                        'official' => 'United States of America'
                    ],
                    'cca2' => 'US',
                    'cca3' => 'USA',
                    'region' => 'Americas',
                    'subregion' => 'North America',
                    'capital' => ['Washington, D.C.'],
                    'population' => 331000000,
                    'area' => 9833520.0,
                    'latlng' => [37.0, -95.0],
                    'timezones' => ['UTC-05:00'],
                    'currencies' => [
                        'USD' => [
                            'name' => 'US Dollar',
                            'symbol' => '$'
                        ]
                    ],
                    'languages' => [
                        'eng' => 'English'
                    ],
                    'flags' => [
                        'png' => 'https://flagcdn.com/w320/us.png',
                        'svg' => 'https://flagcdn.com/us.svg'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/countries/search?q=United');

        $response->assertStatus(200)
            ->assertJsonFragment(['country' => 'United States', 'iso2' => 'US']);
    }

    /** @test */
    public function test_it_dispatches_sync_countries_job_correctly(): void
    {
        Queue::fake();

        SyncCountriesJob::dispatch();

        Queue::assertPushed(SyncCountriesJob::class);
    }
}
