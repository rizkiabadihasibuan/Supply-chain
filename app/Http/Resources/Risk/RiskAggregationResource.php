<?php

declare(strict_types=1);

namespace App\Http\Resources\Risk;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class RiskAggregationResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $this can be a RiskAggregationDTO or an array representing aggregated data.
        $data = is_array($this->resource) ? $this->resource : $this->resource->toArray();

        return [
            'country' => $data['countryName'] ?? '',
            'iso_code' => $data['isoCode'] ?? '',
            'weather' => [
                'temperature' => $data['weather']['temperature'] ?? null,
                'rainfall' => $data['weather']['rainfall'] ?? null,
                'wind_speed' => $data['weather']['wind_speed'] ?? null,
                'condition' => $data['weather']['condition'] ?? 'Unknown',
            ],
            'exchange_rate' => [
                'currency' => $data['exchangeRate']['currency'] ?? 'XXX',
                'rate' => $data['exchangeRate']['rate'] ?? null,
            ],
            'economic' => [
                'gdp' => $data['economic']['gdp'] ?? null,
                'inflation' => $data['economic']['inflation'] ?? null,
                'population' => $data['economic']['population'] ?? null,
            ],
            'political_news' => $data['news'] ?? [],
            'port_information' => $data['ports'] ?? [],
            'timestamp' => $data['timestamp'] ?? now()->toIso8601String(),
        ];
    }
}
