<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\Port;
use App\Repositories\Interfaces\PortRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PortRepository extends BaseRepository implements PortRepositoryInterface
{
    /**
     * PortRepository constructor.
     *
     * @param Port $model
     */
    public function __construct(Port $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByCode(string $code): ?Port
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

        if (!empty($filters['country_id'])) {
            $query->byCountry((int) $filters['country_id']);
        }

        if (!empty($filters['category_id'])) {
            $query->byCategory((int) $filters['category_id']);
        }

        if (!empty($filters['size'])) {
            $query->bySize($filters['size']);
        }

        return $query->get();
    }
}
