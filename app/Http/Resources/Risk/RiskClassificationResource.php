<?php

declare(strict_types=1);

namespace App\Http\Resources\Risk;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class RiskClassificationResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Support mapping both RiskClassification Model and RiskClassificationDTO
        if (is_array($this->resource)) {
            $data = $this->resource;
        } elseif ($this->resource instanceof \App\DTOs\RiskClassificationDTO) {
            $data = $this->resource->toArray();
        } else {
            // Eloquent model fallback
            $priority = match ($this->name) {
                'Very Low' => 'Monitor',
                'Low' => 'Observe',
                'Medium' => 'Attention',
                'High' => 'Immediate Action',
                'Critical' => 'Emergency',
                default => 'Observe',
            };

            $data = [
                'countryName' => $this->relationLoaded('riskScores') && $this->riskScores->first() && $this->riskScores->first()->country 
                    ? $this->riskScores->first()->country->name 
                    : 'Unknown',
                'overallScore' => $this->relationLoaded('riskScores') && $this->riskScores->first() 
                    ? (float) $this->riskScores->first()->overall_score 
                    : 0.0,
                'riskLevel' => $this->name,
                'riskColor' => $this->color_code,
                'priority' => $priority,
                'recommendationLevel' => $priority,
                'classificationTime' => $this->updated_at ? $this->updated_at->toIso8601String() : now()->toIso8601String(),
            ];
        }

        return [
            'country' => $data['countryName'] ?? '',
            'overall_score' => $data['overallScore'] ?? 0.0,
            'risk_level' => $data['riskLevel'] ?? 'Low',
            'risk_color' => $data['riskColor'] ?? '#6B7280',
            'priority' => $data['priority'] ?? 'Monitor',
            'recommendation_level' => $data['recommendationLevel'] ?? 'Observe',
            'classification_time' => $data['classificationTime'] ?? now()->toIso8601String(),
        ];
    }
}