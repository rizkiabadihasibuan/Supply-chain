<?php

declare(strict_types=1);

namespace App\DTOs;

class RiskClassificationDTO
{
    public readonly string $countryName;
    public readonly float $overallScore;
    public readonly string $riskLevel;
    public readonly string $riskColor;
    public readonly string $priority;
    public readonly string $recommendationLevel;
    public readonly string $classificationTime;

    public function __construct(array $data)
    {
        $this->countryName = $data['countryName'] ?? '';
        $this->overallScore = (float) ($data['overallScore'] ?? 0.0);
        $this->riskLevel = $data['riskLevel'] ?? 'Low';
        $this->riskColor = $data['riskColor'] ?? '#6B7280';
        $this->priority = $data['priority'] ?? 'Monitor';
        $this->recommendationLevel = $data['recommendationLevel'] ?? 'Observe';
        $this->classificationTime = $data['classificationTime'] ?? now()->toIso8601String();
    }

    public function toArray(): array
    {
        return [
            'countryName' => $this->countryName,
            'overallScore' => $this->overallScore,
            'riskLevel' => $this->riskLevel,
            'riskColor' => $this->riskColor,
            'priority' => $this->priority,
            'recommendationLevel' => $this->recommendationLevel,
            'classificationTime' => $this->classificationTime,
        ];
    }
}
