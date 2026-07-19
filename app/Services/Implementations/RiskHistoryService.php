<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\RiskHistory;
use App\Repositories\Interfaces\RiskHistoryRepositoryInterface;
use App\Services\Contracts\RiskHistoryServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RiskHistoryService implements RiskHistoryServiceInterface
{
    /**
     * @var RiskHistoryRepositoryInterface
     */
    protected RiskHistoryRepositoryInterface $historyRepo;

    /**
     * RiskHistoryService constructor.
     *
     * @param RiskHistoryRepositoryInterface $historyRepo
     */
    public function __construct(RiskHistoryRepositoryInterface $historyRepo)
    {
        $this->historyRepo = $historyRepo;
    }

    /**
     * @inheritDoc
     */
    public function recordHistory(
        int $countryId,
        int $riskScoreId,
        float $totalScore,
        string $level,
        string $date
    ): RiskHistory {
        return $this->historyRepo->saveHistory($countryId, $riskScoreId, $totalScore, $level, $date);
    }

    /**
     * @inheritDoc
     */
    public function getCountryRiskHistory(int $countryId, int $limit = 30): Collection
    {
        return $this->historyRepo->getHistoryByCountry($countryId, $limit);
    }
}
