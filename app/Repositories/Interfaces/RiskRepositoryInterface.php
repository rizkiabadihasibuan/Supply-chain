<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\RiskComponent;
use App\Models\RiskScore;
use Illuminate\Database\Eloquent\Collection;

interface RiskRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find latest RiskScore by country.
     *
     * @param int $countryId
     * @return RiskScore|null
     */
    public function findByCountry(int $countryId): ?RiskScore;

    /**
     * Search country risk scores by country name.
     *
     * @param string $term
     * @return Collection<int, RiskScore>
     */
    public function search(string $term): Collection;

    /**
     * Filter risk scores by level, classification.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, RiskScore>
     */
    public function filter(array $filters): Collection;

    /**
     * Save risk score calculation.
     *
     * @param int $countryId
     * @param array<string, mixed> $scoreData
     * @return RiskScore
     */
    public function saveRiskScore(int $countryId, array $scoreData): RiskScore;

    /**
     * Save component breakdowns for a risk calculation.
     *
     * @param int $riskScoreId
     * @param int $categoryId
     * @param string $indicator
     * @param float $raw
     * @param float $weight
     * @param float $weighted
     * @return RiskComponent
     */
    public function saveRiskComponent(
        int $riskScoreId,
        int $categoryId,
        string $indicator,
        float $raw,
        float $weight,
        float $weighted
    ): RiskComponent;

    /**
     * Get list of country risks with critical alerts.
     *
     * @return Collection<int, RiskScore>
     */
    public function getCriticalRisks(): Collection;
}
