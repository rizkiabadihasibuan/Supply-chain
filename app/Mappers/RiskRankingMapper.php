<?php

declare(strict_types=1);

namespace App\Mappers;

use App\DTOs\RiskRankingDTO;
use Illuminate\Support\Collection;

class RiskRankingMapper
{
    /**
     * Map a sorted collection of RiskScores to an array of RiskRankingDTO.
     *
     * @param Collection|array $scores
     * @return array<RiskRankingDTO>
     */
    public function mapMany(Collection|array $scores): array
    {
        $dtos = [];
        $rank = 1;

        foreach ($scores as $score) {
            $dtos[] = new RiskRankingDTO([
                'rank' => $rank++,
                'countryName' => $score->country ? $score->country->name : 'Unknown',
                'isoCode' => $score->country ? $score->country->code : '',
                'overallScore' => (float) $score->overall_score,
                'riskLevel' => $score->risk_level,
                'weatherScore' => (float) $score->weather_score,
                'economicScore' => (float) $score->economic_score,
                'politicalScore' => (float) $score->political_score,
                'logisticsScore' => (float) $score->logistics_score,
                'lastUpdated' => $score->updated_at ? $score->updated_at->toIso8601String() : now()->toIso8601String(),
            ]);
        }

        return $dtos;
    }
}
