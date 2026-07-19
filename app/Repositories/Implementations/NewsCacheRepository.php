<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\NewsCache;
use App\Repositories\Interfaces\NewsCacheRepositoryInterface;
use Carbon\Carbon;

class NewsCacheRepository extends BaseRepository implements NewsCacheRepositoryInterface
{
    /**
     * NewsCacheRepository constructor.
     *
     * @param NewsCache $model
     */
    public function __construct(NewsCache $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getCache(string $query): ?NewsCache
    {
        return $this->model->valid()
            ->byQuery($query)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function saveCache(string $query, array $newsData, int $ttlHours = 6): NewsCache
    {
        return $this->model->updateOrCreate(
            ['query' => strtoupper(trim($query))],
            [
                'news_data' => $newsData,
                'expires_at' => Carbon::now()->addHours($ttlHours),
            ]
        );
    }
}
