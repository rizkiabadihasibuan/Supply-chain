<?php

namespace App\Integrations\RestCountries;

class CountryMapper
{
    /**
     * Map Rest Countries API item to CountryDTO
     */
    public function map(array $item): CountryDTO
    {
        // Extract currency
        $currencyCode = '';
        $currencyName = '';
        $currencySymbol = '';
        if (isset($item['currencies']) && is_array($item['currencies'])) {
            $currencyKeys = array_keys($item['currencies']);
            if (!empty($currencyKeys)) {
                $currencyCode = $currencyKeys[0];
                $currencyName = $item['currencies'][$currencyCode]['name'] ?? '';
                $currencySymbol = $item['currencies'][$currencyCode]['symbol'] ?? '';
            }
        }

        // Extract languages
        $languages = [];
        if (isset($item['languages']) && is_array($item['languages'])) {
            $languages = $item['languages'];
        }

        // Extract capital
        $capital = '';
        if (isset($item['capital']) && is_array($item['capital']) && !empty($item['capital'])) {
            $capital = $item['capital'][0];
        }

        // Extract latlng
        $lat = 0.0;
        $lng = 0.0;
        if (isset($item['latlng']) && is_array($item['latlng']) && count($item['latlng']) >= 2) {
            $lat = (float) $item['latlng'][0];
            $lng = (float) $item['latlng'][1];
        }

        return new CountryDTO([
            'officialName' => $item['name']['official'] ?? '',
            'commonName' => $item['name']['common'] ?? '',
            'iso2' => $item['cca2'] ?? '',
            'iso3' => $item['cca3'] ?? '',
            'region' => $item['region'] ?? '',
            'subregion' => $item['subregion'] ?? '',
            'capital' => $capital,
            'population' => $item['population'] ?? 0,
            'area' => $item['area'] ?? 0.0,
            'latitude' => $lat,
            'longitude' => $lng,
            'timezones' => $item['timezones'] ?? [],
            'currencyCode' => $currencyCode,
            'currencyName' => $currencyName,
            'currencySymbol' => $currencySymbol,
            'languages' => $languages,
            'flagUrl' => $item['flags']['png'] ?? $item['flags']['gif'] ?? '',
            'svgPath' => $item['flags']['svg'] ?? '',
        ]);
    }

    /**
     * Map multiple API countries
     *
     * @return array<CountryDTO>
     */
    public function mapMany(array $items): array
    {
        return array_map([$this, 'map'], $items);
    }
}
