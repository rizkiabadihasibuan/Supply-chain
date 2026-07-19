<?php

namespace App\Integrations\ExchangeRate;

class ExchangeRateDTO
{
    public readonly string $baseCurrency;
    public readonly array $rates;
    public readonly string $timestamp;
    
    public function __construct(array $data)
    {
        $this->baseCurrency = $data['baseCurrency'] ?? 'USD';
        $this->rates = $data['rates'] ?? [];
        $this->timestamp = $data['timestamp'] ?? now()->toIso8601String();
    }
}