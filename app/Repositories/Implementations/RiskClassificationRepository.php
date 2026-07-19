<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\RiskClassification;
use App\Models\RiskScore;
use App\Repositories\Interfaces\RiskClassificationRepositoryInterface;

class RiskClassificationRepository extends BaseRepository implements RiskClassificationRepositoryInterface
{
    public function __construct(RiskClassification $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getClassificationByScore(float $score): ?RiskClassification
    {
        return $this->model->newQuery()
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function updateScoreClassification(int $riskScoreId, int $classificationId, string $riskLevel): bool
    {
        return (bool) RiskScore::query()
            ->where('id', $riskScoreId)
            ->update([
                'classification_id' => $classificationId,
                'risk_level' => $riskLevel,
            ]);
    }
}
