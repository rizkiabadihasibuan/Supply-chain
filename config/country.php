<?php
/**
 * CONFIG – country.php
 * Konfigurasi untuk REST Countries & World Bank API Integration
 * TODO (Backend Phase): Implementasi API call
 */

return [
    'rest_countries_url' => env('REST_COUNTRIES_URL', 'https://restcountries.com/v3.1'),
    'world_bank_url'     => env('WORLD_BANK_URL', 'https://api.worldbank.org/v2'),
    'cache_ttl'          => env('COUNTRY_CACHE_TTL', 86400),
    'fields'             => ['name', 'capital', 'region', 'population', 'gdp'],
];
