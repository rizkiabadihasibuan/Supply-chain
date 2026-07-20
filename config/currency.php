<?php
/**
 * CONFIG – currency.php
 * Konfigurasi untuk ExchangeRate API Integration
 * TODO (Backend Phase): Implementasi API call
 */

return [
    'provider'   => env('CURRENCY_PROVIDER', 'exchangerate-api'),
    'api_key'    => env('EXCHANGERATE_API_KEY', ''),
    'base_url'   => env('EXCHANGERATE_BASE_URL', 'https://v6.exchangerate-api.com/v6'),
    'base_currency' => env('CURRENCY_BASE', 'USD'),
    'cache_ttl'  => env('CURRENCY_CACHE_TTL', 3600),
];
