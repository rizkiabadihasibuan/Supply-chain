<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\NewsArticle;
use Illuminate\Database\Eloquent\Collection;

interface NewsRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search news articles.
     *
     * @param string $term
     * @return Collection<int, NewsArticle>
     */
    public function search(string $term): Collection;

    /**
     * Filter news articles.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, NewsArticle>
     */
    public function filter(array $filters): Collection;

    /**
     * Save downloaded news article.
     *
     * @param array<string, mixed> $data
     * @return NewsArticle
     */
    public function saveArticle(array $data): NewsArticle;

    /**
     * Get all news categories.
     *
     * @return Collection
     */
    public function getCategories(): Collection;

    /**
     * Get all active news sources.
     *
     * @return Collection
     */
    public function getSources(): Collection;
}
