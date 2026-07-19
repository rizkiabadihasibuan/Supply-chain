<?php

declare(strict_types=1);

namespace App\DTOs;

class RiskAggregationDTO
{
    public readonly string $countryName;
    public readonly string $isoCode;
    public readonly array $weather;
    public readonly array $exchangeRate;
    public readonly array $economic;
    public readonly array $news;
    public readonly array $ports;
    public readonly string $timestamp;

    public function __construct(array $data)
    {
        $this->countryName = $data['countryName'] ?? '';
        $this->isoCode = $data['isoCode'] ?? '';
        
        $this->weather = [
            'temperature' => $data['weather']['temperature'] ?? null,
            'rainfall' => $data['weather']['rainfall'] ?? null,
            'wind_speed' => $data['weather']['wind_speed'] ?? null,
            'condition' => $data['weather']['condition'] ?? 'Unknown',
        ];

        $this->exchangeRate = [
            'currency' => $data['exchangeRate']['currency'] ?? 'XXX',
            'rate' => $data['exchangeRate']['rate'] ?? null,
        ];

        $this->economic = [
            'gdp' => $data['economic']['gdp'] ?? null,
            'inflation' => $data['economic']['inflation'] ?? null,
            'population' => $data['economic']['population'] ?? null,
        ];

        $this->news = $data['news'] ?? [];
        $this->ports = $data['ports'] ?? [];
        $this->timestamp = $data['timestamp'] ?? now()->toIso8601String();
    }

    public function toArray(): array
    {
        return [
            'countryName' => $this->countryName,
            'isoCode' => $this->isoCode,
            'weather' => $this->weather,
            'exchangeRate' => $this->exchangeRate,
            'economic' => $this->economic,
            'news' => $this->news,
            'ports' => $this->ports,
            'timestamp' => $this->timestamp,
        ];
    }
}
