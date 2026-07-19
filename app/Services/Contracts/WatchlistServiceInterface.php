<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\Watchlist;
use Illuminate\Database\Eloquent\Collection;

interface WatchlistServiceInterface
{
    /**
     * Get watchlists of a user.
     *
     * @param int $userId
     * @return Collection<int, Watchlist>
     */
    public function getUserWatchlists(int $userId): Collection;

    /**
     * Create a new watchlist.
     *
     * @param int $userId
     * @param string $name
     * @param string|null $description
     * @return Watchlist
     */
    public function createWatchlist(int $userId, string $name, ?string $description = null): Watchlist;

    /**
     * Add country to watchlist.
     *
     * @param int $watchlistId
     * @param int $countryId
     * @return void
     */
    public function addCountryToWatchlist(int $watchlistId, int $countryId): void;

    /**
     * Remove country from watchlist.
     *
     * @param int $watchlistId
     * @param int $countryId
     * @return void
     */
    public function removeCountryFromWatchlist(int $watchlistId, int $countryId): void;
}
