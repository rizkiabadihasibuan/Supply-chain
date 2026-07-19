<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

interface ArticleRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find article by its slug.
     *
     * @param string $slug
     * @return Article|null
     */
    public function findBySlug(string $slug): ?Article;

    /**
     * Search articles.
     *
     * @param string $term
     * @return Collection<int, Article>
     */
    public function search(string $term): Collection;

    /**
     * Filter articles by category, status, or tag.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Article>
     */
    public function filter(array $filters): Collection;

    /**
     * Get all published articles.
     *
     * @return Collection<int, Article>
     */
    public function getPublished(): Collection;

    /**
     * Get all draft articles.
     *
     * @return Collection<int, Article>
     */
    public function getDrafts(): Collection;

    /**
     * Synchronize tags for an article.
     *
     * @param int $articleId
     * @param array<int, int> $tagIds
     * @return void
     */
    public function syncTags(int $articleId, array $tagIds): void;
}
