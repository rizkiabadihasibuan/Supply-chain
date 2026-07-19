<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\RiskHistory;
use Illuminate\Database\Eloquent\Collection;

interface RiskHistoryServiceInterface
{
    /**
     * Save daily/weekly/monthly risk score to history.
     *
     * @param int $countryId
     * @param int $riskScoreId
     * @param float $totalScore
     * @param string $level
     * @param string $date
     * @return RiskHistory
     */
    public function recordHistory(
        int $countryId,
        int $riskScoreId,
        float $totalScore,
        string $level,
        string $date
    ): RiskHistory;

    /**
     * Get specific country risk history data points.
     *
     * @param int $countryId
     * @param int $limit
     * @return Collection<int, RiskHistory>
     */
    public function getCountryRiskHistory(int $countryId, int $limit = 30): Collection;
}
