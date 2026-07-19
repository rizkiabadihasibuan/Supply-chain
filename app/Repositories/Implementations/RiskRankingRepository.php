<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\RiskScore;
use App\Repositories\Interfaces\RiskRankingRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RiskRankingRepository extends BaseRepository implements RiskRankingRepositoryInterface
{
    public function __construct(RiskScore $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getLatestScoresForRanking(
        array $filters = [],
        string $sortBy = 'overall_score',
        string $direction = 'desc',
        ?int $limit = null
    ): Collection {
        $query = $this->model->newQuery()
            ->with(['country.region']);

        // Subquery to get only the latest risk score per country
        $query->whereIn('id', function ($sub) {
            $sub->select(DB::raw('MAX(id)'))
                ->from('risk_scores')
                ->groupBy('country_id');
        });

        // Filters
        if (!empty($filters['region_id'])) {
            $query->whereHas('country', function ($q) use ($filters) {
                $q->where('region_id', $filters['region_id']);
            });
        }

        if (!empty($filters['subregion'])) {
            $query->whereHas('country', function ($q) use ($filters) {
                $q->where('subregion', $filters['subregion']);
            });
        }

        if (!empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }

        // Sorting Validation
        $allowedSorts = ['overall_score', 'weather_score', 'economic_score', 'political_score', 'logistics_score'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'overall_score';
        }

        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $direction);

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }
}
