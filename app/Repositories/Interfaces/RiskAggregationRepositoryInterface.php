<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTOs\RiskAggregationDTO;
use App\Models\RiskSnapshot;

interface RiskAggregationRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Save a new risk snapshot.
     *
     * @param int $countryId
     * @param RiskAggregationDTO $dto
     * @return RiskSnapshot
     */
    public function saveSnapshot(int $countryId, RiskAggregationDTO $dto): RiskSnapshot;

    /**
     * Get the latest risk snapshot for a country.
     *
     * @param int $countryId
     * @return RiskSnapshot|null
     */
    public function getLatestSnapshot(int $countryId): ?RiskSnapshot;
}
