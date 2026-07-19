<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\RiskScore;
use Illuminate\Database\Eloquent\Collection;

interface RiskServiceInterface
{
    /**
     * Get latest risk score details by country.
     *
     * @param int $countryId
     * @return RiskScore|null
     */
    public function getCountryRiskScore(int $countryId): ?RiskScore;

    /**
     * Search country risk scores.
     *
     * @param string $term
     * @return Collection<int, RiskScore>
     */
    public function searchRiskScores(string $term): Collection;

    /**
     * Filter country risk scores.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, RiskScore>
     */
    public function filterRiskScores(array $filters): Collection;

    /**
     * Get list of country risk scores with critical risk status.
     *
     * @return Collection<int, RiskScore>
     */
    public function getCriticalCountryRisks(): Collection;
}
