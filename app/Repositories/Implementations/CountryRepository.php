<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\Country;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CountryRepository extends BaseRepository implements CountryRepositoryInterface
{
    /**
     * CountryRepository constructor.
     *
     * @param Country $model
     */
    public function __construct(Country $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByCode(string $code): ?Country
    {
        return $this->model->where('code', strtoupper(trim($code)))->first();
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->where('name', 'like', '%' . $term . '%')
            ->orWhere('code', 'like', '%' . $term . '%')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters): Collection
    {
        $query = $this->model->newQuery();

        if (!empty($filters['region_id'])) {
            $query->byRegion((int) $filters['region_id']);
        }

        if (!empty($filters['subregion'])) {
            $query->where('subregion', $filters['subregion']);
        }

        return $query->get();
    }
}
