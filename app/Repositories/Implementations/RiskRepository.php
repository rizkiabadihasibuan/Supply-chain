<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\RiskComponent;
use App\Models\RiskScore;
use App\Repositories\Interfaces\RiskRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class RiskRepository extends BaseRepository implements RiskRepositoryInterface
{
    /**
     * RiskRepository constructor.
     *
     * @param RiskScore $model
     */
    public function __construct(RiskScore $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByCountry(int $countryId): ?RiskScore
    {
        return $this->model->where('country_id', $countryId)->first();
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

        if (!empty($filters['classification_id'])) {
            $query->byClassification((int) $filters['classification_id']);
        }

        if (!empty($filters['risk_level'])) {
            $query->where('risk_level', $filters['risk_level']);
        }

        return $query->latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function saveRiskScore(int $countryId, array $scoreData): RiskScore
    {
        return $this->model->updateOrCreate(
            ['country_id' => $countryId],
            array_merge($scoreData, [
                'calculated_at' => Carbon::now(),
            ])
        );
    }

    /**
     * @inheritDoc
     */
    public function saveRiskComponent(
        int $riskScoreId,
        int $categoryId,
        string $indicator,
        float $raw,
        float $weight,
        float $weighted
    ): RiskComponent {
        return RiskComponent::updateOrCreate(
            [
                'risk_score_id' => $riskScoreId,
                'category_id' => $categoryId,
                'indicator_name' => $indicator,
            ],
            [
                'raw_value' => $raw,
                'weight' => $weight,
                'weighted_score' => $weighted,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getCriticalRisks(): Collection
    {
        return $this->model->critical()->get();
    }
}
