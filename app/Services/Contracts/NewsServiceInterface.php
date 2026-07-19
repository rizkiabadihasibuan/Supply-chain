<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\NewsArticle;
use Illuminate\Database\Eloquent\Collection;

interface NewsServiceInterface
{
    /**
     * Get news feed from GNews (checks cache, else fetches from external API).
     *
     * @param string $query
     * @param bool $forceRefresh
     * @return array<int, mixed>
     */
    public function fetchNews(string $query, bool $forceRefresh = false): array;

    /**
     * Search local cached news articles.
     *
     * @param string $term
     * @return Collection<int, NewsArticle>
     */
    public function searchNews(string $term): Collection;

    /**
     * Filter news articles.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, NewsArticle>
     */
    public function filterNews(array $filters): Collection;
}
