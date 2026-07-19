<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\RiskScore;

interface RiskCalculatorServiceInterface
{
    /**
     * Calculate country risk score.
     * Weights: Weather = 30%, Inflation = 20%, Political News = 40%, Currency = 10%.
     *
     * @param int $countryId
     * @param float $weatherRaw
     * @param float $inflationRaw
     * @param float $politicalRaw
     * @param float $currencyRaw
     * @return RiskScore
     */
    public function calculateCountryRisk(
        int $countryId,
        float $weatherRaw,
        float $inflationRaw,
        float $politicalRaw,
        float $currencyRaw
    ): RiskScore;
}
