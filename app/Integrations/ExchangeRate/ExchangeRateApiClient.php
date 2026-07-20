<?php

namespace App\Integrations\ExchangeRate;

use App\Integrations\Clients\BaseApiClient;
use Illuminate\Support\Facades\Config;

class ExchangeRateApiClient extends BaseApiClient
{
    protected function getBaseUrl(): string
    {
        return Config::get('api.integrations.exchange_rate.base_url', 'https://open.er-api.com/v6/latest');
    }

    /**
     * Fetch latest exchange rates for a base currency (real-time)
     */
    public function getLatest(string $base = 'USD'): array
    {
        return $this->get('/' . strtoupper($base));
    }
}