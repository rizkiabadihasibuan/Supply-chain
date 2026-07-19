<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\RiskWeight;
use App\Repositories\Interfaces\RiskWeightRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class RiskWeightRepository extends BaseRepository implements RiskWeightRepositoryInterface
{
    /**
     * RiskWeightRepository constructor.
     *
     * @param RiskWeight $model
     */
    public function __construct(RiskWeight $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->whereHas('category', function ($q) use ($term) {
            $q->where('name', 'like', '%' . $term . '%');
        })->get();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters): Collection
    {
        $query = $this->model->newQuery();

        if (isset($filters['is_active'])) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        return $query->latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function getActiveWeights(): Collection
    {
        return $this->model->active()->get();
    }

    /**
     * @inheritDoc
     */
    public function saveWeight(int $categoryId, float $weight, string $effectiveDate): RiskWeight
    {
        // Deactivate older weights of the same category if this is active
        $this->model->where('category_id', $categoryId)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        return $this->create([
            'category_id' => $categoryId,
            'weight' => $weight,
            'effective_date' => Carbon::parse($effectiveDate),
            'is_active' => true,
        ]);
    }
}
