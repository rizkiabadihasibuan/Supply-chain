<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\RiskScore;
use App\Repositories\Interfaces\RiskRepositoryInterface;
use App\Services\Contracts\RiskServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RiskService implements RiskServiceInterface
{
    /**
     * @var RiskRepositoryInterface
     */
    protected RiskRepositoryInterface $riskRepo;

    /**
     * RiskService constructor.
     *
     * @param RiskRepositoryInterface $riskRepo
     */
    public function __construct(RiskRepositoryInterface $riskRepo)
    {
        $this->riskRepo = $riskRepo;
    }

    /**
     * @inheritDoc
     */
    public function getCountryRiskScore(int $countryId): ?RiskScore
    {
        return $this->riskRepo->findByCountry($countryId);
    }

    /**
     * @inheritDoc
     */
    public function searchRiskScores(string $term): Collection
    {
        return $this->riskRepo->search($term);
    }

    /**
     * @inheritDoc
     */
    public function filterRiskScores(array $filters): Collection
    {
        return $this->riskRepo->filter($filters);
    }

    /**
     * @inheritDoc
     */
    public function getCriticalCountryRisks(): Collection
    {
        return $this->riskRepo->getCriticalRisks();
    }
}
