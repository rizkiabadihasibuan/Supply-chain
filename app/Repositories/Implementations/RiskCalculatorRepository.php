<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\DTOs\RiskScoreDTO;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use App\Repositories\Interfaces\RiskCalculatorRepositoryInterface;

class RiskCalculatorRepository extends BaseRepository implements RiskCalculatorRepositoryInterface
{
    public function __construct(RiskScore $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function saveCalculatedScore(int $countryId, RiskScoreDTO $dto): RiskScore
    {
        $classification = RiskClassification::where('min_score', '<=', $dto->overallScore)
            ->where('max_score', '>=', $dto->overallScore)
            ->first();
            
        $classificationId = $classification ? $classification->id : 1;

        return $this->create([
            'country_id' => $countryId,
            'classification_id' => $classificationId,
            'weather_score' => $dto->weatherScore,
            'economic_score' => $dto->economicScore,
            'political_score' => $dto->politicalScore,
            'logistics_score' => $dto->logisticsScore,
            'overall_score' => $dto->overallScore,
            'final_risk_score' => $dto->overallScore,
            'risk_level' => $dto->riskLevel,
            'calculated_at' => $dto->calculatedAt,
            'source_version' => '1.0.0',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getLatestScore(int $countryId): ?RiskScore
    {
        return $this->model->newQuery()
            ->where('country_id', $countryId)
            ->orderBy('calculated_at', 'desc')
            ->first();
    }
}
