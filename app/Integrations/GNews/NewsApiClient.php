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
        return Config::get('api.integrations.gnews.api_key', config('services.gnews.key', ''));
    }

    /**
     * Search news based on keywords (Logistics, Shipping, Economy, Trade, etc)
     */
    public function search(string $keyword = 'logistics OR shipping OR economy OR trade', string $lang = 'en'): array
    {
        $params = [
            'q' => $keyword,
            'lang' => $lang,
            'max' => 10,
        ];

        $apiKey = $this->getApiKey();
        if (!empty($apiKey)) {
            $params['apikey'] = $apiKey;
        }

        return $this->get('/search', $params);
    }

    /**
     * Fetch top news for logistics / business category
     */
    public function topHeadlines(string $category = 'business', string $lang = 'en'): array
    {
        $params = [
            'category' => $category,
            'lang' => $lang,
            'max' => 10,
        ];

        $apiKey = $this->getApiKey();
        if (!empty($apiKey)) {
            $params['apikey'] = $apiKey;
        }

        return $this->get('/top-headlines', $params);
    }
}