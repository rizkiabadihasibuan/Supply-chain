<?php

namespace App\Integrations\RestCountries;

use App\Integrations\Clients\BaseApiClient;
use Illuminate\Support\Facades\Config;

class RestCountriesApiClient extends BaseApiClient
{
    protected function getBaseUrl(): string
    {
        return Config::get('api.integrations.rest_countries.base_url', 'https://restcountries.com/v3.1');
    }

    /**
     * Get all countries
     */
    public function all(): array
    {
        return $this->get('/all');
    }

    /**
     * Get country by ISO code (CCA2/CCA3)
     */
    public function byCode(string $code): array
    {
        return $this->get("/alpha/{$code}");
    }

    /**
     * Get countries by region
     */
    public function byRegion(string $region): array
    {
        return $this->get("/region/{$region}");
    }
}
