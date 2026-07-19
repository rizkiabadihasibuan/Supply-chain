<?php

declare(strict_types=1);

namespace App\Http\Resources\Risk;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class RiskScoreResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Support mapping both RiskScore Model and RiskScoreDTO
        if (is_array($this->resource)) {
            $data = $this->resource;
        } elseif ($this->resource instanceof \App\DTOs\RiskScoreDTO) {
            $data = $this->resource->toArray();
        } else {
            // Eloquent Model
            $data = [
                'countryId' => $this->country_id,
                'weatherScore' => (float) $this->weather_score,
                'economicScore' => (float) $this->economic_score,
                'politicalScore' => (float) $this->political_score,
                'logisticsScore' => (float) $this->logistics_score,
                'overallScore' => (float) $this->overall_score,
                'riskLevel' => $this->risk_level,
                'calculatedAt' => $this->calculated_at ? $this->calculated_at->toIso8601String() : null,
            ];
        }

        return [
            'country_id' => $data['countryId'] ?? 0,
            'weather_score' => $data['weatherScore'] ?? 0.0,
            'economic_score' => $data['economicScore'] ?? 0.0,
            'political_score' => $data['politicalScore'] ?? 0.0,
            'logistics_score' => $data['logisticsScore'] ?? 0.0,
            'overall_score' => $data['overallScore'] ?? 0.0,
            'risk_level' => $data['riskLevel'] ?? 'Low',
            'calculated_at' => $data['calculatedAt'] ?? now()->toIso8601String(),
        ];
    }
}