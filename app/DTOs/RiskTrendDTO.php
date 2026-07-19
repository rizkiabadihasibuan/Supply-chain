<?php

declare(strict_types=1);

namespace App\DTOs;

class RiskTrendDTO
{
    public readonly string $countryName;
    public readonly float $currentScore;
    public readonly float $previousScore;
    public readonly float $scoreDifference;
    public readonly float $percentageChange;
    public readonly int $previousRank;
    public readonly int $currentRank;
    public readonly int $rankDifference;
    public readonly int $classificationDifference;
    public readonly string $trendDirection;
    public readonly string $trendStrength;
    public readonly string $analysisTime;

    public function __construct(array $data)
    {
        $this->countryName = $data['countryName'] ?? '';
        $this->currentScore = (float) ($data['currentScore'] ?? 0.0);
        $this->previousScore = (float) ($data['previousScore'] ?? 0.0);
        $this->scoreDifference = (float) ($data['scoreDifference'] ?? 0.0);
        $this->percentageChange = (float) ($data['percentageChange'] ?? 0.0);
        $this->previousRank = (int) ($data['previousRank'] ?? 0);
        $this->currentRank = (int) ($data['currentRank'] ?? 0);
        $this->rankDifference = (int) ($data['rankDifference'] ?? 0);
        $this->classificationDifference = (int) ($data['classificationDifference'] ?? 0);
        $this->trendDirection = $data['trendDirection'] ?? 'Stable';
        $this->trendStrength = $data['trendStrength'] ?? 'Weak';
        $this->analysisTime = $data['analysisTime'] ?? now()->toIso8601String();
    }

    public function toArray(): array
    {
        return [
            'countryName' => $this->countryName,
            'currentScore' => $this->currentScore,
            'previousScore' => $this->previousScore,
            'scoreDifference' => $this->scoreDifference,
            'percentageChange' => $this->percentageChange,
            'previousRank' => $this->previousRank,
            'currentRank' => $this->currentRank,
            'rankDifference' => $this->rankDifference,
            'classificationDifference' => $this->classificationDifference,
            'trendDirection' => $this->trendDirection,
            'trendStrength' => $this->trendStrength,
            'analysisTime' => $this->analysisTime,
        ];
    }
}
