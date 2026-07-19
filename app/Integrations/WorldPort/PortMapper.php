<?php

namespace App\Integrations\WorldPort;

class PortMapper
{
    /**
     * Map World Port Index JSON Response to array of Internal PortDTO
     */
    public function mapCollection(array $response): array
    {
        $ports = $response['features'] ?? [];
        
        $dtos = [];
        foreach ($ports as $port) {
            $props = $port['attributes'] ?? [];
            $geom = $port['geometry'] ?? [];
            
            $dtos[] = new PortDTO([
                'portCode' => $props['index_no'] ?? '',
                'name' => $props['port_name'] ?? '',
                'country' => $props['country'] ?? '',
                'latitude' => $geom['y'] ?? 0.0,
                'longitude' => $geom['x'] ?? 0.0,
                'status' => $props['harborsize'] ?? 'Unknown',
                'harborSize' => $props['harborsize'] ?? 'Unknown',
                'harborType' => $props['harbortype'] ?? 'Unknown',
                'services' => [
                    'supplies' => $props['supplies_prov'] ?? 'N',
                    'repairs' => $props['repairs'] ?? 'N',
                    'drydock' => $props['drydock'] ?? 'N',
                    'fuel' => $props['fuel'] ?? 'N',
                    'water' => $props['water'] ?? 'N',
                ],
            ]);
        }
        
        return $dtos;
    }
}