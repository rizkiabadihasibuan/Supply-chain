<?php

declare(strict_types=1);

namespace App\Mappers;

use App\DTOs\RiskTrendDTO;

class RiskTrendMapper
{
    /**
     * Map trend analysis metrics to RiskTrendDTO.
     */
    public function map(
        string $countryName,
        float $currentScore,
        float $previousScore,
        float $scoreDifference,
        float $percentageChange,
        int $previousRank,
        int $currentRank,
        int $rankDifference,
        int $classificationDifference,
        string $trendDirection,
        string $trendStrength
    ): RiskTrendDTO {
        return new RiskTrendDTO([
            'countryName' => $countryName,
            'currentScore' => $currentScore,
            'previousScore' => $previousScore,
            'scoreDifference' => $scoreDifference,
            'percentageChange' => $percentageChange,
            'previousRank' => $previousRank,
            'currentRank' => $currentRank,
            'rankDifference' => $rankDifference,
            'classificationDifference' => $classificationDifference,
            'trendDirection' => $trendDirection,
            'trendStrength' => $trendStrength,
            'analysisTime' => now()->toIso8601String(),
        ]);
    }
}
