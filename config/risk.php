<?php
/**
 * CONFIG – risk.php
 * Konfigurasi untuk Risk Scoring Engine
 * TODO (Backend Phase): Implementasi kalkulasi risk score
 */

return [
    'weights' => [
        'weather'    => 0.30,
        'economic'   => 0.20,
        'political'  => 0.40,
        'logistics'  => 0.10,
    ],
    'thresholds' => [
        'very_low' => 20,
        'low'      => 40,
        'medium'   => 60,
        'high'     => 80,
        'critical' => 100,
    ],
    'cache_ttl' => env('RISK_CACHE_TTL', 3600),
    'trend_thresholds' => [
        'weak'     => 2.0,
        'moderate' => 5.0,
        'strong'   => 10.0,
        'critical' => 15.0,
    ],
    'alert_rules' => [
        'overall_risk' => [
            'enabled' => true,
            'threshold' => 75.0,
            'severity' => 'High',
        ],
        'weather' => [
            'enabled' => true,
            'threshold' => 70.0,
            'severity' => 'Medium',
        ],
        'economic' => [
            'enabled' => true,
            'threshold' => 60.0,
            'severity' => 'Medium',
        ],
        'political' => [
            'enabled' => true,
            'threshold' => 65.0,
            'severity' => 'High',
        ],
        'logistics' => [
            'enabled' => true,
            'threshold' => 70.0,
            'severity' => 'Medium',
        ],
        'trend' => [
            'enabled' => true,
            'threshold' => 15.0,
            'severity' => 'High',
        ],
        'ranking' => [
            'enabled' => true,
            'threshold' => 3,
            'severity' => 'Medium',
        ],
        'classification_change' => [
            'enabled' => true,
            'severity' => 'Info',
        ],
        'data_quality' => [
            'enabled' => true,
            'severity' => 'Low',
        ],
    ],
];
