<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\RiskHistory;
use Illuminate\Database\Eloquent\Collection;

interface RiskHistoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search risk history items (by country name).
     *
     * @param string $term
     * @return Collection<int, RiskHistory>
     */
    public function search(string $term): Collection;

    /**
     * Filter risk history.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, RiskHistory>
     */
    public function filter(array $filters): Collection;

    /**
     * Save daily/weekly calculated country risk history.
     *
     * @param int $countryId
     * @param int $riskScoreId
     * @param float $totalScore
     * @param string $level
     * @param string $date
     * @return RiskHistory
     */
    public function saveHistory(
        int $countryId,
        int $riskScoreId,
        float $totalScore,
        string $level,
        string $date
    ): RiskHistory;

    /**
     * Get country history scores.
     *
     * @param int $countryId
     * @param int $limit
     * @return Collection<int, RiskHistory>
     */
    public function getHistoryByCountry(int $countryId, int $limit = 30): Collection;
}
