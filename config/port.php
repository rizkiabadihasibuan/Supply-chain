<?php
/**
 * CONFIG – port.php
 * Konfigurasi untuk World Port Index API Integration
 * TODO (Backend Phase): Implementasi API call
 */

return [
    'provider'  => env('PORT_PROVIDER', 'world-port-index'),
    'base_url'  => env('PORT_BASE_URL', ''),
    'api_key'   => env('PORT_API_KEY', ''),
    'cache_ttl' => env('PORT_CACHE_TTL', 86400),
];
