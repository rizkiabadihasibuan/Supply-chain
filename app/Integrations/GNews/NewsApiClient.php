<?php

namespace App\Integrations\GNews;

use App\Integrations\Clients\BaseApiClient;
use Illuminate\Support\Facades\Config;

class NewsApiClient extends BaseApiClient
{
    protected function getBaseUrl(): string
    {
        return Config::get('api.integrations.gnews.base_url', 'https://gnews.io/api/v4');
    }

    protected function getApiKey(): string
    {
        return Config::get('api.integrations.gnews.api_key', '');
    }

    /**
     * Search news based on keywords (Political Risk, Supply Chain, etc)
     */
    public function search(string $keyword, string $country = 'us', string $lang = 'en'): array
    {
        return $this->get('/search', [
            'q' => $keyword,
            'country' => $country,
            'lang' => $lang,
            'apikey' => $this->getApiKey(),
            'max' => 10
        ]);
    }

    /**
     * Fetch top news for a specific category (e.g. business, nation)
     */
    public function topHeadlines(string $category = 'business', string $country = 'us', string $lang = 'en'): array
    {
        return $this->get('/top-headlines', [
            'category' => $category,
            'country' => $country,
            'lang' => $lang,
            'apikey' => $this->getApiKey(),
            'max' => 10
        ]);
    }
}