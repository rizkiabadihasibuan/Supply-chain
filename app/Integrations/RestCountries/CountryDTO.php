<?php

namespace App\Integrations\RestCountries;

class CountryDTO
{
    public readonly string $officialName;
    public readonly string $commonName;
    public readonly string $iso2;
    public readonly string $iso3;
    public readonly string $region;
    public readonly string $subregion;
    public readonly string $capital;
    public readonly int $population;
    public readonly float $area;
    public readonly float $latitude;
    public readonly float $longitude;
    public readonly array $timezones;
    public readonly string $currencyCode;
    public readonly string $currencyName;
    public readonly string $currencySymbol;
    public readonly array $languages;
    public readonly string $flagUrl;
    public readonly string $svgPath;

    public function __construct(array $data)
    {
        $this->officialName = $data['officialName'] ?? '';
        $this->commonName = $data['commonName'] ?? '';
        $this->iso2 = $data['iso2'] ?? '';
        $this->iso3 = $data['iso3'] ?? '';
        $this->region = $data['region'] ?? '';
        $this->subregion = $data['subregion'] ?? '';
        $this->capital = $data['capital'] ?? '';
        $this->population = (int) ($data['population'] ?? 0);
        $this->area = (float) ($data['area'] ?? 0.0);
        $this->latitude = (float) ($data['latitude'] ?? 0.0);
        $this->longitude = (float) ($data['longitude'] ?? 0.0);
        $this->timezones = $data['timezones'] ?? [];
        $this->currencyCode = $data['currencyCode'] ?? '';
        $this->currencyName = $data['currencyName'] ?? '';
        $this->currencySymbol = $data['currencySymbol'] ?? '';
        $this->languages = $data['languages'] ?? [];
        $this->flagUrl = $data['flagUrl'] ?? '';
        $this->svgPath = $data['svgPath'] ?? '';
    }
}
