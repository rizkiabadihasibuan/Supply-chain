<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\RiskTrend;
use App\DTOs\RiskTrendDTO;
use App\Repositories\Interfaces\RiskTrendRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RiskTrendRepository extends BaseRepository implements RiskTrendRepositoryInterface
{
    public function __construct(RiskTrend $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getForCountry(int $countryId, int $limit = 10): Collection
    {
        return $this->model->newQuery()
            ->where('country_id', $countryId)
            ->orderBy('analyzed_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function saveTrend(int $countryId, RiskTrendDTO $dto): RiskTrend
    {
        return $this->create([
            'country_id' => $countryId,
            'trend_type' => 'overall',
            'previous_score' => $dto->previousScore,
            'current_score' => $dto->currentScore,
            'change_percentage' => $dto->percentageChange,
            'trend_direction' => $dto->trendDirection,
            'analyzed_at' => $dto->analysisTime,
        ]);
    }
}
