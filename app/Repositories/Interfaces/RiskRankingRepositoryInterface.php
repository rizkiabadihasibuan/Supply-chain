<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface RiskRankingRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get sorted and filtered latest risk scores for ranking.
     * Prevents N+1 by eager loading country/region dependencies.
     *
     * @param array $filters
     * @param string $sortBy
     * @param string $direction
     * @param int|null $limit
     * @return Collection
     */
    public function getLatestScoresForRanking(
        array $filters = [],
        string $sortBy = 'overall_score',
        string $direction = 'desc',
        ?int $limit = null
    ): Collection;
}
