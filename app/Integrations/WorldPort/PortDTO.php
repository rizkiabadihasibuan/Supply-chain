<?php

namespace App\Integrations\WorldPort;

class PortDTO
{
    public readonly string $portCode;
    public readonly string $name;
    public readonly string $country;
    public readonly float $latitude;
    public readonly float $longitude;
    public readonly string $status;
    public readonly string $harborSize;
    public readonly string $harborType;
    public readonly array $services;
    
    public function __construct(array $data)
    {
        $this->portCode = $data['portCode'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->country = $data['country'] ?? '';
        $this->latitude = $data['latitude'] ?? 0.0;
        $this->longitude = $data['longitude'] ?? 0.0;
        $this->status = $data['status'] ?? 'Unknown';
        $this->harborSize = $data['harborSize'] ?? 'Unknown';
        $this->harborType = $data['harborType'] ?? 'Unknown';
        $this->services = $data['services'] ?? [];
    }
}