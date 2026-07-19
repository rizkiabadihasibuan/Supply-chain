<?php
/**
 * CONFIG – weather.php
 * Konfigurasi untuk Open-Meteo API Integration
 * TODO (Backend Phase): Implementasi API call
 */

return [
    'provider'  => env('WEATHER_PROVIDER', 'open-meteo'),
    'base_url'  => env('WEATHER_BASE_URL', 'https://api.open-meteo.com/v1'),
    'cache_ttl' => env('WEATHER_CACHE_TTL', 600),  // 10 minutes
    'units'     => env('WEATHER_UNITS', 'celsius'),
];
