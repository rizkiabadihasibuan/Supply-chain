<?php
/**
 * CONFIG – news.php
 * Konfigurasi untuk GNews API Integration
 * TODO (Backend Phase): Implementasi API call
 */

return [
    'provider'  => env('NEWS_PROVIDER', 'gnews'),
    'api_key'   => env('GNEWS_API_KEY', ''),
    'base_url'  => env('GNEWS_BASE_URL', 'https://gnews.io/api/v4'),
    'language'  => env('NEWS_LANGUAGE', 'en'),
    'max_items' => env('NEWS_MAX_ITEMS', 20),
    'cache_ttl' => env('NEWS_CACHE_TTL', 1800),
    'topics'    => ['supply-chain', 'logistics', 'trade', 'economy'],
];
