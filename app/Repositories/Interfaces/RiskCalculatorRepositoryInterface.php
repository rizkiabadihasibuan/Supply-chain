<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTOs\RiskScoreDTO;
use App\Models\RiskScore;

interface RiskCalculatorRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Save a calculated risk score.
     *
     * @param int $countryId
     * @param RiskScoreDTO $dto
     * @return RiskScore
     */
    public function saveCalculatedScore(int $countryId, RiskScoreDTO $dto): RiskScore;

    /**
     * Get latest risk score for a country.
     *
     * @param int $countryId
     * @return RiskScore|null
     */
    public function getLatestScore(int $countryId): ?RiskScore;
}
