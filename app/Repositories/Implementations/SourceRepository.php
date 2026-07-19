<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\NewsSource;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SourceRepository extends BaseRepository implements SourceRepositoryInterface
{
    /**
     * SourceRepository constructor.
     *
     * @param NewsSource $model
     */
    public function __construct(NewsSource $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->where('name', 'like', '%' . $term . '%')->get();
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

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function getActiveSources(): Collection
    {
        return $this->model->active()->get();
    }
}
