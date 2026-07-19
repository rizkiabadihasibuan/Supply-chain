<?php

declare(strict_types=1);

namespace App\Http\Resources\Risk;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class RiskTrendResource extends BaseResource
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
            'country' => $data['countryName'] ?? '',
            'current_score' => $data['currentScore'] ?? 0.0,
            'previous_score' => $data['previousScore'] ?? 0.0,
            'score_difference' => $data['scoreDifference'] ?? 0.0,
            'percentage_change' => $data['percentageChange'] ?? 0.0,
            'previous_rank' => $data['previousRank'] ?? 0,
            'current_rank' => $data['currentRank'] ?? 0,
            'rank_difference' => $data['rankDifference'] ?? 0,
            'classification_difference' => $data['classificationDifference'] ?? 0,
            'trend_direction' => $data['trendDirection'] ?? 'Stable',
            'trend_strength' => $data['trendStrength'] ?? 'Weak',
            'analysis_time' => $data['analysisTime'] ?? now()->toIso8601String(),
        ];
    }
}
