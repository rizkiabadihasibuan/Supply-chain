<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\Watchlist;
use App\Repositories\Interfaces\WatchlistRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WatchlistRepository extends BaseRepository implements WatchlistRepositoryInterface
{
    /**
     * WatchlistRepository constructor.
     *
     * @param Watchlist $model
     */
    public function __construct(Watchlist $model)
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

        if (!empty($filters['user_id'])) {
            $query->where('user_id', (int) $filters['user_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    /**
     * @inheritDoc
     */
    public function addCountry(int $watchlistId, int $countryId): void
    {
        $watchlist = $this->findById($watchlistId);

        if ($watchlist) {
            $watchlist->countries()->attach($countryId);
        }
    }

    /**
     * @inheritDoc
     */
    public function removeCountry(int $watchlistId, int $countryId): void
    {
        $watchlist = $this->findById($watchlistId);

        if ($watchlist) {
            $watchlist->countries()->detach($countryId);
        }
    }
}
