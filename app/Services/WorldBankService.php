<?php

namespace App\Services;

use App\Integrations\WorldBank\WorldBankApiClient;
use App\Integrations\WorldBank\WorldBankMapper;
use App\Integrations\WorldBank\WorldBankCacheManager;
use App\Integrations\WorldBank\WorldBankDTO;
use Illuminate\Support\Facades\Log;

class WorldBankService
{
    protected $apiClient;
    protected $mapper;
    protected $cacheManager;

    // World Bank Indicators
    const IND_GDP = 'NY.GDP.MKTP.CD';
    const IND_INFLATION = 'FP.CPI.TOTL.ZG';
    const IND_IMPORTS = 'NE.IMP.GNFS.CD';
    const IND_EXPORTS = 'NE.EXP.GNFS.CD';
    const IND_POPULATION = 'SP.POP.TOTL';

    public function __construct(
        WorldBankApiClient $apiClient,
        WorldBankMapper $mapper,
        WorldBankCacheManager $cacheManager
    ) {
        $this->apiClient = $apiClient;
        $this->mapper = $mapper;
        $this->cacheManager = $cacheManager;
    }

    /**
     * Get Economic Indicators for Country
     */
    public function getEconomicData(string $countryCode, bool $forceRefresh = false): WorldBankDTO
    {
        $rawData = $this->cacheManager->getCachedIndicator($countryCode, function () use ($countryCode) {
            try {
                // Fetch all indicators (Sequential for simplicity, can be parallelized in real world)
                return [
                    'gdp' => $this->apiClient->getIndicator($countryCode, self::IND_GDP),
                    'inflation' => $this->apiClient->getIndicator($countryCode, self::IND_INFLATION),
                    'imports' => $this->apiClient->getIndicator($countryCode, self::IND_IMPORTS),
                    'exports' => $this->apiClient->getIndicator($countryCode, self::IND_EXPORTS),
                    'population' => $this->apiClient->getIndicator($countryCode, self::IND_POPULATION),
                ];
            } catch (\Exception $e) {
                Log::error("WorldBank API fallback exception for {$countryCode}: " . $e->getMessage());
                throw $e;
            }
        }, $forceRefresh);

        return $this->mapper->map(
            $countryCode,
            $rawData['gdp'],
            $rawData['inflation'],
            $rawData['imports'],
            $rawData['exports'],
            $rawData['population']
        );
    }
}