<?php

declare(strict_types=1);

namespace App\Mappers;

use App\DTOs\RiskScoreDTO;

class RiskScoreMapper
{
    /**
     * Map scores to RiskScoreDTO.
     */
    public function map(
        int $countryId,
        float $weatherScore,
        float $economicScore,
        float $politicalScore,
        float $logisticsScore,
        float $overallScore,
        string $riskLevel
    ): RiskScoreDTO {
        return new RiskScoreDTO([
            'countryId' => $countryId,
            'weatherScore' => $weatherScore,
            'economicScore' => $economicScore,
            'politicalScore' => $politicalScore,
            'logisticsScore' => $logisticsScore,
            'overallScore' => $overallScore,
            'riskLevel' => $riskLevel,
            'calculatedAt' => now()->toIso8601String(),
        ]);
    }
}
