<?php

declare(strict_types=1);

namespace App\Mappers;

use App\DTOs\RiskClassificationDTO;

class RiskClassificationMapper
{
    /**
     * Map raw details to RiskClassificationDTO.
     */
    public function map(
        string $countryName,
        float $overallScore,
        string $riskLevel,
        string $colorCode
    ): RiskClassificationDTO {
        $priority = match ($riskLevel) {
            'Very Low' => 'Monitor',
            'Low' => 'Observe',
            'Medium' => 'Attention',
            'High' => 'Immediate Action',
            'Critical' => 'Emergency',
            default => 'Observe',
        };

        $recommendationLevel = match ($riskLevel) {
            'Very Low' => 'Monitor',
            'Low' => 'Observe',
            'Medium' => 'Attention',
            'High' => 'Immediate Action',
            'Critical' => 'Emergency',
            default => 'Observe',
        };

        return new RiskClassificationDTO([
            'countryName' => $countryName,
            'overallScore' => $overallScore,
            'riskLevel' => $riskLevel,
            'riskColor' => $colorCode,
            'priority' => $priority,
            'recommendationLevel' => $recommendationLevel,
            'classificationTime' => now()->toIso8601String(),
        ]);
    }
}
