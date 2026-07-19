<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\Article;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository extends BaseRepository implements ArticleRepositoryInterface
{
    /**
     * ArticleRepository constructor.
     *
     * @param Article $model
     */
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findBySlug(string $slug): ?Article
    {
        return $this->model->where('slug', $slug)->first();
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

        if (!empty($filters['category_id'])) {
            $query->byCategory((int) $filters['category_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function getPublished(): Collection
    {
        return $this->model->published()->get();
    }

    /**
     * @inheritDoc
     */
    public function getDrafts(): Collection
    {
        return $this->model->draft()->latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function syncTags(int $articleId, array $tagIds): void
    {
        $article = $this->findById($articleId);

        if ($article) {
            $article->tags()->sync($tagIds);
        }
    }
}
