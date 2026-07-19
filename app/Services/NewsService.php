<?php

namespace App\Services;

use App\Integrations\GNews\NewsApiClient;
use App\Integrations\GNews\NewsMapper;
use App\Integrations\GNews\NewsCacheManager;
use Illuminate\Support\Facades\Log;

class NewsService
{
    protected $apiClient;
    protected $mapper;
    protected $cacheManager;

    public function __construct(
        NewsApiClient $apiClient,
        NewsMapper $mapper,
        NewsCacheManager $cacheManager
    ) {
        $this->apiClient = $apiClient;
        $this->mapper = $mapper;
        $this->cacheManager = $cacheManager;
    }

    /**
     * Get Political Risk / Supply Chain News
     */
    public function getRiskIntelligenceNews(string $country = 'us', bool $forceRefresh = false): array
    {
        $keyword = '"supply chain" OR "political risk" OR "economic sanction" OR "trade war" OR "port strike"';
        $identifier = md5($keyword . $country);
        
        $rawData = $this->cacheManager->getCachedNews('search', $identifier, function () use ($keyword, $country) {
            try {
                return $this->apiClient->search($keyword, $country);
            } catch (\Exception $e) {
                Log::error("GNews API fallback exception (Search): " . $e->getMessage());
                throw $e;
            }
        }, $forceRefresh);

        $dtos = $this->mapper->mapCollection($rawData);
        
        // At this point, we could deduplicate articles based on URL
        // and inject NewsRepository to save them to database.
        
        return $dtos;
    }
}