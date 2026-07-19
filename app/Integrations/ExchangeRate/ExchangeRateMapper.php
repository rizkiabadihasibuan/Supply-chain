<?php

namespace App\Integrations\ExchangeRate;

class ExchangeRateMapper
{
    /**
     * Map Exchange Rate JSON Response to Internal ExchangeRateDTO
     */
    public function map(array $response): ExchangeRateDTO
    {
        return new ExchangeRateDTO([
            'baseCurrency' => $response['base'] ?? 'USD',
            'rates' => $response['rates'] ?? [],
            'timestamp' => isset($response['time_last_updated_unix']) 
                ? date('c', $response['time_last_updated_unix']) 
                : now()->toIso8601String(),
        ]);
    }
}