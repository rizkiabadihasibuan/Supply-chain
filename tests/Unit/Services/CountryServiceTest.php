<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CountryService;
use App\Integrations\RestCountries\CountryDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CountryService::class);
        Cache::flush();
    }

    /** @test */
    public function it_can_get_all_countries(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([
                [
                    'name' => ['common' => 'Indonesia', 'official' => 'Republic of Indonesia'],
                    'cca2' => 'ID',
                    'cca3' => 'IDN',
                    'region' => 'Asia',
                    'subregion' => 'South-Eastern Asia',
                    'capital' => ['Jakarta'],
                    'population' => 273523621,
                    'area' => 1904569.0,
                    'latlng' => [-5.0, 120.0],
                    'timezones' => ['UTC+07:00'],
                    'currencies' => ['IDR' => ['name' => 'Indonesian rupiah', 'symbol' => 'Rp']],
                    'languages' => ['ind' => 'Indonesian'],
                    'flags' => ['png' => 'https://flagcdn.com/w320/id.png', 'svg' => 'https://flagcdn.com/id.svg']
                ]
            ], 200)
        ]);

        $countries = $this->service->getAllCountries();

        $this->assertIsArray($countries);
        $this->assertCount(1, $countries);
        $this->assertEquals('Indonesia', $countries[0]['country']);
        $this->assertEquals('ID', $countries[0]['iso2']);
        $this->assertEquals('IDN', $countries[0]['iso3']);
    }

    /** @test */
    public function it_can_find_country_by_name_case_insensitive(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([
                [
                    'name' => ['common' => 'Indonesia', 'official' => 'Republic of Indonesia'],
                    'cca2' => 'ID',
                    'cca3' => 'IDN',
                    'region' => 'Asia',
                    'subregion' => 'South-Eastern Asia',
                    'capital' => ['Jakarta'],
                    'population' => 273523621,
                    'area' => 1904569.0,
                    'latlng' => [-5.0, 120.0],
                    'timezones' => ['UTC+07:00'],
                    'currencies' => ['IDR' => ['name' => 'Indonesian rupiah', 'symbol' => 'Rp']],
                    'languages' => ['ind' => 'Indonesian'],
                    'flags' => ['png' => 'https://flagcdn.com/w320/id.png', 'svg' => 'https://flagcdn.com/id.svg']
                ]
            ], 200)
        ]);

        $countryLower = $this->service->getCountryByName('indonesia');
        $countryUpper = $this->service->getCountryByName('INDONESIA');
        $countryMixed = $this->service->getCountryByName('InDoNeSiA');

        $this->assertNotNull($countryLower);
        $this->assertNotNull($countryUpper);
        $this->assertNotNull($countryMixed);
        $this->assertEquals('Indonesia', $countryLower['country']);
    }

    /** @test */
    public function it_can_get_country_by_iso2(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([
                [
                    'name' => ['common' => 'United States', 'official' => 'United States of America'],
                    'cca2' => 'US',
                    'cca3' => 'USA',
                    'region' => 'Americas',
                    'subregion' => 'North America',
                    'capital' => ['Washington, D.C.'],
                    'population' => 331000000,
                    'area' => 9833520.0,
                    'latlng' => [37.0, -95.0],
                    'timezones' => ['UTC-05:00'],
                    'currencies' => ['USD' => ['name' => 'US Dollar', 'symbol' => '$']],
                    'languages' => ['eng' => 'English'],
                    'flags' => ['png' => 'https://flagcdn.com/w320/us.png', 'svg' => 'https://flagcdn.com/us.svg']
                ]
            ], 200)
        ]);

        $country = $this->service->getCountryByISO2('US');

        $this->assertNotNull($country);
        $this->assertEquals('United States', $country['country']);
        $this->assertEquals('US', $country['iso2']);
    }

    /** @test */
    public function it_can_get_country_by_iso3(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([
                [
                    'name' => ['common' => 'Germany', 'official' => 'Federal Republic of Germany'],
                    'cca2' => 'DE',
                    'cca3' => 'DEU',
                    'region' => 'Europe',
                    'subregion' => 'Western Europe',
                    'capital' => ['Berlin'],
                    'population' => 83240525,
                    'area' => 357022.0,
                    'latlng' => [51.5, 10.0],
                    'timezones' => ['UTC+01:00'],
                    'currencies' => ['EUR' => ['name' => 'Euro', 'symbol' => '€']],
                    'languages' => ['deu' => 'German'],
                    'flags' => ['png' => 'https://flagcdn.com/w320/de.png', 'svg' => 'https://flagcdn.com/de.svg']
                ]
            ], 200)
        ]);

        $country = $this->service->getCountryByISO3('DEU');

        $this->assertNotNull($country);
        $this->assertEquals('Germany', $country['country']);
        $this->assertEquals('DEU', $country['iso3']);
    }

    /** @test */
    public function it_can_search_countries(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([
                [
                    'name' => ['common' => 'Brazil', 'official' => 'Federative Republic of Brazil'],
                    'cca2' => 'BR',
                    'cca3' => 'BRA',
                    'region' => 'Americas',
                    'subregion' => 'South America',
                    'capital' => ['Brasília'],
                    'population' => 215313498,
                    'area' => 8514877.0,
                    'latlng' => [-10.0, -55.0],
                    'timezones' => ['UTC-03:00'],
                    'currencies' => ['BRL' => ['name' => 'Brazilian real', 'symbol' => 'R$']],
                    'languages' => ['por' => 'Portuguese'],
                    'flags' => ['png' => 'https://flagcdn.com/w320/br.png', 'svg' => 'https://flagcdn.com/br.svg']
                ]
            ], 200)
        ]);

        $results = $this->service->searchCountry('Brazil');

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertEquals('Brazil', $results[0]['country']);
    }

    /** @test */
    public function it_can_get_coordinates(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([
                [
                    'name' => ['common' => 'Japan', 'official' => 'Japan'],
                    'cca2' => 'JP',
                    'cca3' => 'JPN',
                    'region' => 'Asia',
                    'subregion' => 'East Asia',
                    'capital' => ['Tokyo'],
                    'population' => 125124989,
                    'area' => 377975.0,
                    'latlng' => [36.0, 138.0],
                    'timezones' => ['UTC+09:00'],
                    'currencies' => ['JPY' => ['name' => 'Japanese yen', 'symbol' => '¥']],
                    'languages' => ['jpn' => 'Japanese'],
                    'flags' => ['png' => 'https://flagcdn.com/w320/jp.png', 'svg' => 'https://flagcdn.com/jp.svg']
                ]
            ], 200)
        ]);

        $coordinates = $this->service->getCoordinates('Japan');

        $this->assertNotNull($coordinates);
        $this->assertEquals(36.0, $coordinates['latitude']);
        $this->assertEquals(138.0, $coordinates['longitude']);
    }

    /** @test */
    public function it_can_get_currency(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([
                [
                    'name' => ['common' => 'Australia', 'official' => 'Commonwealth of Australia'],
                    'cca2' => 'AU',
                    'cca3' => 'AUS',
                    'region' => 'Oceania',
                    'subregion' => 'Oceania',
                    'capital' => ['Canberra'],
                    'population' => 26068792,
                    'area' => 7692024.0,
                    'latlng' => [-27.0, 133.0],
                    'timezones' => ['UTC+08:00'],
                    'currencies' => ['AUD' => ['name' => 'Australian dollar', 'symbol' => '$']],
                    'languages' => ['eng' => 'English'],
                    'flags' => ['png' => 'https://flagcdn.com/w320/au.png', 'svg' => 'https://flagcdn.com/au.svg']
                ]
            ], 200)
        ]);

        $currency = $this->service->getCurrency('Australia');

        $this->assertNotNull($currency);
        $this->assertEquals('AUD', $currency['currency']);
        $this->assertEquals('$', $currency['currency_symbol']);
    }

    /** @test */
    public function it_can_get_country_summary(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([
                [
                    'name' => ['common' => 'France', 'official' => 'French Republic'],
                    'cca2' => 'FR',
                    'cca3' => 'FRA',
                    'region' => 'Europe',
                    'subregion' => 'Western Europe',
                    'capital' => ['Paris'],
                    'population' => 67391582,
                    'area' => 643801.0,
                    'latlng' => [46.0, 2.0],
                    'timezones' => ['UTC+01:00'],
                    'currencies' => ['EUR' => ['name' => 'Euro', 'symbol' => '€']],
                    'languages' => ['fra' => 'French'],
                    'flags' => ['png' => 'https://flagcdn.com/w320/fr.png', 'svg' => 'https://flagcdn.com/fr.svg']
                ]
            ], 200)
        ]);

        $summary = $this->service->getCountrySummary('France');

        $this->assertNotNull($summary);
        $this->assertEquals('France', $summary['country']);
        $this->assertEquals('FR', $summary['iso2']);
        $this->assertEquals('FRA', $summary['iso3']);
        $this->assertEquals('Paris', $summary['capital']);
        $this->assertEquals('Europe', $summary['region']);
    }

    /** @test */
    public function it_returns_null_for_non_existent_country(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([], 200)
        ]);

        $country = $this->service->getCountryByName('NonExistentCountry');

        $this->assertNull($country);
    }

    /** @test */
    public function it_uses_cache_for_subsequent_requests(): void
    {
        Http::fake([
            'https://restcountries.com/v3.1/all' => Http::response([
                [
                    'name' => ['common' => 'Spain', 'official' => 'Kingdom of Spain'],
                    'cca2' => 'ES',
                    'cca3' => 'ESP',
                    'region' => 'Europe',
                    'subregion' => 'Southern Europe',
                    'capital' => ['Madrid'],
                    'population' => 47560635,
                    'area' => 505990.0,
                    'latlng' => [40.0, -4.0],
                    'timezones' => ['UTC+01:00'],
                    'currencies' => ['EUR' => ['name' => 'Euro', 'symbol' => '€']],
                    'languages' => ['spa' => 'Spanish'],
                    'flags' => ['png' => 'https://flagcdn.com/w320/es.png', 'svg' => 'https://flagcdn.com/es.svg']
                ]
            ], 200)
        ]);

        // First request
        $country1 = $this->service->getCountryByName('Spain');
        
        // Second request should use cache
        $country2 = $this->service->getCountryByName('Spain');

        $this->assertNotNull($country1);
        $this->assertNotNull($country2);
        $this->assertEquals($country1['country'], $country2['country']);

        // Verify API was called only once (cache was used for second request)
        Http::assertSentCount(1);
    }
}

