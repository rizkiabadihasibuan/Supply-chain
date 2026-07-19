<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Watchlist;
use Illuminate\Database\Eloquent\Collection;

interface WatchlistRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search watchlists by name.
     *
     * @param string $term
     * @return Collection<int, Watchlist>
     */
    public function search(string $term): Collection;

    /**
     * Filter watchlists.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Watchlist>
     */
    public function filter(array $filters): Collection;

    /**
     * Get watchlists of a specific user.
     *
     * @param int $userId
     * @return Collection<int, Watchlist>
     */
    public function getByUser(int $userId): Collection;

    /**
     * Add country to watchlist.
     *
     * @param int $watchlistId
     * @param int $countryId
     * @return void
     */
    public function addCountry(int $watchlistId, int $countryId): void;

    /**
     * Remove country from watchlist.
     *
     * @param int $watchlistId
     * @param int $countryId
     * @return void
     */
    public function removeCountry(int $watchlistId, int $countryId): void;
}
