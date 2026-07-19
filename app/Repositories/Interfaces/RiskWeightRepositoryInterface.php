<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\RiskWeight;
use Illuminate\Database\Eloquent\Collection;

interface RiskWeightRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search risk weights by category name.
     *
     * @param string $term
     * @return Collection<int, RiskWeight>
     */
    public function search(string $term): Collection;

    /**
     * Filter risk weights.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, RiskWeight>
     */
    public function filter(array $filters): Collection;

    /**
     * Get active risk weights list.
     *
     * @return Collection<int, RiskWeight>
     */
    public function getActiveWeights(): Collection;

    /**
     * Save/update weight for a risk category.
     *
     * @param int $categoryId
     * @param float $weight
     * @param string $effectiveDate
     * @return RiskWeight
     */
    public function saveWeight(int $categoryId, float $weight, string $effectiveDate): RiskWeight;
}
