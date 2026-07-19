<?php

namespace App\Services;

use App\Integrations\WorldPort\PortApiClient;
use App\Integrations\WorldPort\PortMapper;
use App\Integrations\WorldPort\PortCacheManager;
use Illuminate\Support\Facades\Log;

class PortService
{
    protected $apiClient;
    protected $mapper;
    protected $cacheManager;

    public function __construct(
        PortApiClient $apiClient,
        PortMapper $mapper,
        PortCacheManager $cacheManager
    ) {
        $this->apiClient = $apiClient;
        $this->mapper = $mapper;
        $this->cacheManager = $cacheManager;
    }

    /**
     * Get Port Data
     */
    public function searchPorts(string $query = '', string $country = '', bool $forceRefresh = false): array
    {
        $identifier = md5($query . $country);
        
        $rawData = $this->cacheManager->getCachedPorts($identifier, function () use ($query, $country) {
            try {
                return $this->apiClient->searchPorts($query, $country);
            } catch (\Exception $e) {
                Log::error("World Port API fallback exception: " . $e->getMessage());
                throw $e;
            }
        }, $forceRefresh);

        $dtos = $this->mapper->mapCollection($rawData);
        
        // In a real application, $this->portRepository->sync($dtos) would go here
        
        return $dtos;
    }
}