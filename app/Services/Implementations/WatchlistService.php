<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\Watchlist;
use App\Repositories\Interfaces\WatchlistRepositoryInterface;
use App\Services\Contracts\WatchlistServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class WatchlistService implements WatchlistServiceInterface
{
    /**
     * @var WatchlistRepositoryInterface
     */
    protected WatchlistRepositoryInterface $watchlistRepo;

    /**
     * WatchlistService constructor.
     *
     * @param WatchlistRepositoryInterface $watchlistRepo
     */
    public function __construct(WatchlistRepositoryInterface $watchlistRepo)
    {
        $this->watchlistRepo = $watchlistRepo;
    }

    /**
     * @inheritDoc
     */
    public function getUserWatchlists(int $userId): Collection
    {
        return $this->watchlistRepo->getByUser($userId);
    }

    /**
     * @inheritDoc
     */
    public function createWatchlist(int $userId, string $name, ?string $description = null): Watchlist
    {
        return $this->watchlistRepo->create([
            'user_id' => $userId,
            'name' => $name,
            'description' => $description,
            'status' => 'active',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function addCountryToWatchlist(int $watchlistId, int $countryId): void
    {
        $this->watchlistRepo->addCountry($watchlistId, $countryId);
    }

    /**
     * @inheritDoc
     */
    public function removeCountryFromWatchlist(int $watchlistId, int $countryId): void
    {
        $this->watchlistRepo->removeCountry($watchlistId, $countryId);
    }
}
