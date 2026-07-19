<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\RiskHistory;
use App\Repositories\Interfaces\RiskHistoryRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class RiskHistoryRepository extends BaseRepository implements RiskHistoryRepositoryInterface
{
    /**
     * RiskHistoryRepository constructor.
     *
     * @param RiskHistory $model
     */
    public function __construct(RiskHistory $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->whereHas('country', function ($q) use ($term) {
            $q->where('name', 'like', '%' . $term . '%');
        })->get();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters): Collection
    {
        $query = $this->model->newQuery();

        if (!empty($filters['country_id'])) {
            $query->byCountry((int) $filters['country_id']);
        }

        if (!empty($filters['period'])) {
            if ($filters['period'] === 'today') {
                $query->today();
            } elseif ($filters['period'] === 'week') {
                $query->thisWeek();
            } elseif ($filters['period'] === 'month') {
                $query->thisMonth();
            }
        }

        return $query->latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function saveHistory(
        int $countryId,
        int $riskScoreId,
        float $totalScore,
        string $level,
        string $date
    ): RiskHistory {
        return $this->model->updateOrCreate(
            [
                'country_id' => $countryId,
                'risk_score_id' => $riskScoreId,
                'calculated_date' => Carbon::parse($date)->toDateString(),
            ],
            [
                'total_risk_score' => $totalScore,
                'risk_level' => $level,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getHistoryByCountry(int $countryId, int $limit = 30): Collection
    {
        return $this->model->byCountry($countryId)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
