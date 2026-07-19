<?php

declare(strict_types=1);

namespace App\DTOs;

class RiskScoreDTO
{
    public readonly int $countryId;
    public readonly float $weatherScore;
    public readonly float $economicScore;
    public readonly float $politicalScore;
    public readonly float $logisticsScore;
    public readonly float $overallScore;
    public readonly string $riskLevel;
    public readonly string $calculatedAt;

    public function __construct(array $data)
    {
        $this->countryId = (int) ($data['countryId'] ?? 0);
        $this->weatherScore = (float) ($data['weatherScore'] ?? 0.0);
        $this->economicScore = (float) ($data['economicScore'] ?? 0.0);
        $this->politicalScore = (float) ($data['politicalScore'] ?? 0.0);
        $this->logisticsScore = (float) ($data['logisticsScore'] ?? 0.0);
        $this->overallScore = (float) ($data['overallScore'] ?? 0.0);
        $this->riskLevel = $data['riskLevel'] ?? 'Low';
        $this->calculatedAt = $data['calculatedAt'] ?? now()->toIso8601String();
    }

    public function toArray(): array
    {
        return [
            'countryId' => $this->countryId,
            'weatherScore' => $this->weatherScore,
            'economicScore' => $this->economicScore,
            'politicalScore' => $this->politicalScore,
            'logisticsScore' => $this->logisticsScore,
            'overallScore' => $this->overallScore,
            'riskLevel' => $this->riskLevel,
            'calculatedAt' => $this->calculatedAt,
        ];
    }
}
