<?php

declare(strict_types=1);

namespace App\DTO\Factories;

use App\DTO\RiskScoreDTO;

class RiskDTOFactory
{
    /**
     * Create RiskScoreDTO from raw scoring variables.
     *
     * @param int $countryId
     * @param float $weather
     * @param float $inflation
     * @param float $currency
     * @param float $political
     * @param float $final
     * @param string $level
     * @return RiskScoreDTO
     */
    public static function create(
        int $countryId,
        float $weather,
        float $inflation,
        float $currency,
        float $political,
        float $final,
        string $level
    ): RiskScoreDTO {
        return RiskScoreDTO::fromArray([
            'country_id' => $countryId,
            'weather_score' => $weather,
            'inflation_score' => $inflation,
            'currency_score' => $currency,
            'political_score' => $political,
            'final_risk_score' => $final,
            'risk_level' => $level,
        ]);
    }
}
