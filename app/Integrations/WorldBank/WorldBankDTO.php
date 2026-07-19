<?php

namespace App\Integrations\WorldBank;

class WorldBankDTO
{
    public readonly string $countryCode;
    public readonly ?float $gdp;
    public readonly ?float $inflation;
    public readonly ?float $imports;
    public readonly ?float $exports;
    public readonly ?int $population;
    public readonly string $timestamp;
    
    public function __construct(array $data)
    {
        $this->countryCode = $data['countryCode'] ?? '';
        $this->gdp = $data['gdp'] ?? null;
        $this->inflation = $data['inflation'] ?? null;
        $this->imports = $data['imports'] ?? null;
        $this->exports = $data['exports'] ?? null;
        $this->population = $data['population'] ?? null;
        $this->timestamp = $data['timestamp'] ?? now()->toIso8601String();
    }
}