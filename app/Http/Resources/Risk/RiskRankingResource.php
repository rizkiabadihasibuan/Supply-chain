<?php

declare(strict_types=1);

namespace App\Http\Resources\Risk;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class RiskRankingResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = is_array($this->resource) ? $this->resource : $this->resource->toArray();

        return [
            'rank' => $data['rank'] ?? 0,
            'country' => $data['countryName'] ?? '',
            'iso_code' => $data['isoCode'] ?? '',
            'overall_score' => $data['overallScore'] ?? 0.0,
            'risk_level' => $data['riskLevel'] ?? 'Low',
            'weather_score' => $data['weatherScore'] ?? 0.0,
            'economic_score' => $data['economicScore'] ?? 0.0,
            'political_score' => $data['politicalScore'] ?? 0.0,
            'logistics_score' => $data['logisticsScore'] ?? 0.0,
            'last_updated' => $data['lastUpdated'] ?? now()->toIso8601String(),
        ];
    }
}
