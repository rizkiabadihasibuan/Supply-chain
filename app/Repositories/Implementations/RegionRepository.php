<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\Region;
use App\Repositories\Interfaces\RegionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RegionRepository extends BaseRepository implements RegionRepositoryInterface
{
    /**
     * RegionRepository constructor.
     *
     * @param Region $model
     */
    public function __construct(Region $model)
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
        return $this->findAll();
    }
}
