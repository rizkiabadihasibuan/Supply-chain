<?php

declare(strict_types=1);

namespace App\DTO\Mappers;

use App\DTO\RiskScoreDTO;
use App\Models\RiskScore;

class RiskMapper
{
    /**
     * Convert RiskScore model to RiskScoreDTO.
     *
     * @param RiskScore $model
     * @return RiskScoreDTO
     */
    public static function toDTO(RiskScore $model): RiskScoreDTO
    {
        return RiskScoreDTO::fromArray([
            'id' => $model->id,
            'country_id' => $model->country_id,
            'classification_id' => $model->classification_id,
            'weather_score' => $model->weather_score,
            'inflation_score' => $model->inflation_score,
            'currency_score' => $model->currency_score,
            'political_score' => $model->political_score,
            'final_risk_score' => $model->final_risk_score,
            'risk_level' => $model->risk_level,
            'calculated_at' => $model->calculated_at?->toDateTimeString(),
            'source_version' => $model->source_version,
        ]);
    }
}
