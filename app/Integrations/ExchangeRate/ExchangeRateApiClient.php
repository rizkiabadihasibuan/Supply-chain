<?php

namespace App\Integrations\ExchangeRate;

use App\Integrations\Clients\BaseApiClient;
use Illuminate\Support\Facades\Config;

class ExchangeRateApiClient extends BaseApiClient
{
    protected function getBaseUrl(): string
    {
        return Config::get('api.integrations.exchange_rate.base_url', 'https://api.exchangerate-api.com/v4/latest');
    }

    /**
     * Fetch latest exchange rates for a base currency
     */
    public function getLatest(string $base = 'USD'): array
    {
        // Many APIs use /{base} or ?base={base} depending on the vendor.
        // Assuming /v4/latest/{base} pattern
        return $this->get('/' . $base);
    }
}