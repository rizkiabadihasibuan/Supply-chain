<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\NewsSource;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
    /**
     * NewsRepository constructor.
     *
     * @param NewsArticle $model
     */
    public function __construct(NewsArticle $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->search($term)->get();
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

        if (!empty($filters['source_id'])) {
            $query->bySource((int) $filters['source_id']);
        }

        if (!empty($filters['sentiment'])) {
            $query->where('sentiment_status', $filters['sentiment']);
        }

        return $query->latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function saveArticle(array $data): NewsArticle
    {
        return NewsArticle::updateOrCreate(
            ['url' => $data['url']],
            $data
        );
    }

    /**
     * @inheritDoc
     */
    public function getCategories(): Collection
    {
        return NewsCategory::all();
    }

    /**
     * @inheritDoc
     */
    public function getSources(): Collection
    {
        return NewsSource::active()->get();
    }
}
