<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\NewsCache;

interface NewsCacheRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get valid news cache for query.
     *
     * @param string $query
     * @return NewsCache|null
     */
    public function getCache(string $query): ?NewsCache;

    /**
     * Save/update news cache.
     *
     * @param string $query
     * @param array<int, mixed> $newsData
     * @param int $ttlHours
     * @return NewsCache
     */
    public function saveCache(string $query, array $newsData, int $ttlHours = 6): NewsCache;
}
