<?php

namespace App\Integrations\WorldBank;

class WorldBankMapper
{
    /**
     * Map World Bank JSON Response to Internal WorldBankDTO
     * World Bank API usually returns [ [{page_meta}], [{data1}, {data2}] ]
     */
    public function map(string $countryCode, array $gdpData, array $inflationData, array $importData, array $exportData, array $populationData): WorldBankDTO
    {
        // Simple extraction of the most recent value from the arrays
        $extractValue = function($dataArray) {
            if (isset($dataArray[1]) && is_array($dataArray[1])) {
                foreach ($dataArray[1] as $item) {
                    if (isset($item['value']) && $item['value'] !== null) {
                        return (float) $item['value'];
                    }
                }
            }
            return null;
        };

        return new WorldBankDTO([
            'countryCode' => $countryCode,
            'gdp' => $extractValue($gdpData),
            'inflation' => $extractValue($inflationData),
            'imports' => $extractValue($importData),
            'exports' => $extractValue($exportData),
            'population' => (int) $extractValue($populationData),
        ]);
    }
}