<?php
/**
 * CONFIG – sentiment.php
 * Konfigurasi untuk Sentiment Analysis Engine
 * TODO (Backend Phase): Implementasi NLP / sentiment scoring
 */

return [
    'provider'  => env('SENTIMENT_PROVIDER', 'internal'),
    'cache_ttl' => env('SENTIMENT_CACHE_TTL', 3600),
    'threshold' => [
        'positive' => 0.6,
        'negative' => 0.4,
    ],
];
