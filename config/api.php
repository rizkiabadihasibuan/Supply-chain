<?php

return [
    'timeout' => env('API_TIMEOUT', 10),
    'cache_ttl' => env('API_CACHE_TTL', 3600),
    
    'integrations' => [
        'open_meteo' => [
            'base_url' => env('OPEN_METEO_URL', 'https://api.open-meteo.com/v1'),
        ],
        'world_bank' => [
            'base_url' => env('WORLD_BANK_URL', 'https://api.worldbank.org/v2'),
        ],
        'exchange_rate' => [
            'base_url' => env('EXCHANGE_RATE_URL', 'https://open.er-api.com/v6/latest'),
            'api_key' => env('EXCHANGE_RATE_API_KEY', env('EXCHANGE_RATE_KEY', '')),
        ],
        'rest_countries' => [
            'base_url' => env('REST_COUNTRIES_URL', 'https://restcountries.com/v3.1'),
        ],
        'gnews' => [
            'base_url' => env('GNEWS_URL', 'https://gnews.io/api/v4'),
            'api_key' => env('GNEWS_API_KEY', env('GNEWS_KEY', '')),
        ],
        'world_port_index' => [
            'base_url' => env('WORLD_PORT_INDEX_URL', 'https://msi.nga.mil/api'),
        ],
    ]
];