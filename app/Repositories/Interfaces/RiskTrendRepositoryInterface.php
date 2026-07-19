<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTOs\RiskTrendDTO;
use App\Models\RiskTrend;
use Illuminate\Database\Eloquent\Collection;

interface RiskTrendRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get trends for a country.
     *
     * @param int $countryId
     * @param int $limit
     * @return Collection
     */
    public function getForCountry(int $countryId, int $limit = 10): Collection;

    /**
     * Save a risk trend analysis record.
     *
     * @param int $countryId
     * @param RiskTrendDTO $dto
     * @return RiskTrend
     */
    public function saveTrend(int $countryId, RiskTrendDTO $dto): RiskTrend;
}
