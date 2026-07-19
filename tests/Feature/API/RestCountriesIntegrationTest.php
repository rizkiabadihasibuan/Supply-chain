<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use App\Services\CountryService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RestCountriesIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected CountryService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->service = app(CountryService::class);
        Cache::flush();
    }

    /** @test */
    public function test_countries_service_integrates_with_rest_countries_and_handles_cache_with_backup(): void
    {
        // 1. Mock REST Countries API success
        Http::fake([
            'restcountries.com/*' => Http::sequence()->push([
                [
                    'name' => [
                        'common' => 'Germany',
                        'official' => 'Federal Republic of Germany',
                    ],
                    'cca2' => 'DE',
                    'cca3' => 'DEU',
                    'region' => 'Europe',
                    'subregion' => 'Western Europe',
                    'capital' => ['Berlin'],
                    'population' => 83240525,
                    'area' => 357114.0,
                    'latlng' => [51.0, 9.0],
                    'timezones' => ['UTC+01:00'],
                    'currencies' => [
                        'EUR' => [
                            'name' => 'Euro',
                            'symbol' => '€',
                        ]
                    ],
                    'languages' => [
                        'deu' => 'German',
                    ],
                    'flags' => [
                        'png' => 'https://flagcdn.com/w320/de.png',
                        'svg' => 'https://flagcdn.com/de.svg',
                    ]
                ]
            ], 200)
            ->pushStatus(500)
            ->pushStatus(500)
        ]);

        // 1st Call - API success
        $countries = $this->service->getAllCountries();
        $this->assertCount(1, $countries);
        $this->assertEquals('Germany', $countries[0]['country']);
        $this->assertEquals('DE', $countries[0]['iso2']);

        // Verify cache keys exist
        $this->assertTrue(Cache::has('rest_countries_all_dtos'));
        $this->assertTrue(Cache::has('rest_countries_all_dtos_backup'));

        // Case insensitivity checks
        $c1 = $this->service->getCountryByName('germany');
        $c2 = $this->service->getCountryByName('Germany');
        $c3 = $this->service->getCountryByName('GERMANY');
        $this->assertNotNull($c1);
        $this->assertEquals($c1['country'], $c2['country']);
        $this->assertEquals($c1['country'], $c3['country']);

        // Coordinates & Currency checks
        $coords = $this->service->getCoordinates('DE');
        $this->assertEquals(51.0, $coords['latitude']);
        $this->assertEquals(9.0, $coords['longitude']);

        $currency = $this->service->getCurrency('Germany');
        $this->assertEquals('EUR', $currency['currency']);
        $this->assertEquals('€', $currency['currency_symbol']);

        // Clear active cache to force fresh call
        Cache::forget('rest_countries_all_dtos');

        // 2nd Call - API fails (500), falls back to backup cache
        $countries2 = $this->service->getAllCountries();
        $this->assertCount(1, $countries2);
        $this->assertEquals('Germany', $countries2[0]['country']);

        // Clear backup cache to test failure exception
        Cache::forget('rest_countries_all_dtos_backup');

        // 3rd Call - API fails, no backup cache -> throws exception
        $this->expectException(\Throwable::class);
        $this->service->getAllCountries();
    }
}
