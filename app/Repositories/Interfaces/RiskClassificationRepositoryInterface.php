<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\RiskClassification;

interface RiskClassificationRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get classification matching the overall score from thresholds.
     *
     * @param float $score
     * @return RiskClassification|null
     */
    public function getClassificationByScore(float $score): ?RiskClassification;

    /**
     * Update a risk score classification details.
     *
     * @param int $riskScoreId
     * @param int $classificationId
     * @param string $riskLevel
     * @return bool
     */
    public function updateScoreClassification(int $riskScoreId, int $classificationId, string $riskLevel): bool;
}
