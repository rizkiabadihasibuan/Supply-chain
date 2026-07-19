<?php

declare(strict_types=1);

namespace App\DTOs;

class RiskRankingDTO
{
    public readonly int $rank;
    public readonly string $countryName;
    public readonly string $isoCode;
    public readonly float $overallScore;
    public readonly string $riskLevel;
    public readonly float $weatherScore;
    public readonly float $economicScore;
    public readonly float $politicalScore;
    public readonly float $logisticsScore;
    public readonly string $lastUpdated;

    public function __construct(array $data)
    {
        $this->rank = (int) ($data['rank'] ?? 0);
        $this->countryName = $data['countryName'] ?? '';
        $this->isoCode = $data['isoCode'] ?? '';
        $this->overallScore = (float) ($data['overallScore'] ?? 0.0);
        $this->riskLevel = $data['riskLevel'] ?? 'Low';
        $this->weatherScore = (float) ($data['weatherScore'] ?? 0.0);
        $this->economicScore = (float) ($data['economicScore'] ?? 0.0);
        $this->politicalScore = (float) ($data['politicalScore'] ?? 0.0);
        $this->logisticsScore = (float) ($data['logisticsScore'] ?? 0.0);
        $this->lastUpdated = $data['lastUpdated'] ?? now()->toIso8601String();
    }

    public function toArray(): array
    {
        return [
            'rank' => $this->rank,
            'countryName' => $this->countryName,
            'isoCode' => $this->isoCode,
            'overallScore' => $this->overallScore,
            'riskLevel' => $this->riskLevel,
            'weatherScore' => $this->weatherScore,
            'economicScore' => $this->economicScore,
            'politicalScore' => $this->politicalScore,
            'logisticsScore' => $this->logisticsScore,
            'lastUpdated' => $this->lastUpdated,
        ];
    }
}
