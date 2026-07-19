<?php

namespace App\Integrations\WorldBank;

use App\Integrations\Clients\BaseApiClient;
use Illuminate\Support\Facades\Config;

class WorldBankApiClient extends BaseApiClient
{
    protected function getBaseUrl(): string
    {
        return Config::get('api.integrations.world_bank.base_url', 'https://api.worldbank.org/v2');
    }

    /**
     * Fetch indicator data for a specific country
     */
    public function getIndicator(string $countryCode, string $indicator): array
    {
        return $this->get("/country/{$countryCode}/indicator/{$indicator}", [
            'format' => 'json',
            'per_page' => 2,
            'mrnev' => 1 // Most recent non-empty value
        ]);
    }
}