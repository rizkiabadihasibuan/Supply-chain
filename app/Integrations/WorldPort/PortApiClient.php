<?php

namespace App\Integrations\WorldPort;

use App\Integrations\Clients\BaseApiClient;
use Illuminate\Support\Facades\Config;

class PortApiClient extends BaseApiClient
{
    protected function getBaseUrl(): string
    {
        // Using NGA (National Geospatial-Intelligence Agency) API endpoint for World Port Index
        return Config::get('api.integrations.world_port_index.base_url', 'https://msi.nga.mil/api/publications/wpi');
    }

    /**
     * Search ports by country or keyword
     */
    public function searchPorts(string $query, string $country = ''): array
    {
        $params = [
            'q' => $query,
            'country' => $country,
            'limit' => 50
        ];
        
        return $this->get('/search', array_filter($params));
    }
}