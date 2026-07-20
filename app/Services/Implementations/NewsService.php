<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Integrations\GNews\NewsApiClient;
use App\Models\NewsArticle;
use App\Repositories\Interfaces\ApiLogRepositoryInterface;
use App\Repositories\Interfaces\NewsCacheRepositoryInterface;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Services\Contracts\NewsServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class NewsService implements NewsServiceInterface
{
    /**
     * @var NewsApiClient
     */
    protected NewsApiClient $apiClient;

    /**
     * @var NewsRepositoryInterface
     */
    protected NewsRepositoryInterface $newsRepo;

    /**
     * @var NewsCacheRepositoryInterface
     */
    protected NewsCacheRepositoryInterface $newsCacheRepo;

    /**
     * @var ApiLogRepositoryInterface
     */
    protected ApiLogRepositoryInterface $apiLogRepo;

    /**
     * NewsService constructor.
     */
    public function __construct(
        NewsApiClient $apiClient,
        NewsRepositoryInterface $newsRepo,
        NewsCacheRepositoryInterface $newsCacheRepo,
        ApiLogRepositoryInterface $apiLogRepo
    ) {
        $this->apiClient = $apiClient;
        $this->newsRepo = $newsRepo;
        $this->newsCacheRepo = $newsCacheRepo;
        $this->apiLogRepo = $apiLogRepo;
    }

    /**
     * @inheritDoc
     */
    public function fetchNews(string $query, bool $forceRefresh = false): array
    {
        $queryString = trim($query);
        if (empty($queryString)) {
            $queryString = 'logistics OR shipping OR economy OR trade';
        }

        $startTime = microtime(true);

        // 1. Always attempt live GNews API fetch first for real-time news
        try {
            $data = $this->apiClient->search($queryString);
            $articles = $data['articles'] ?? [];
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);

            if (!empty($articles)) {
                $this->newsCacheRepo->saveCache($queryString, $articles);

                foreach ($articles as $article) {
                    $this->newsRepo->saveArticle([
                        'title' => $article['title'] ?? 'No Title',
                        'description' => $article['description'] ?? null,
                        'content' => $article['content'] ?? null,
                        'url' => $article['url'] ?? '',
                        'image_url' => $article['image'] ?? null,
                        'author' => $article['source']['name'] ?? null,
                        'published_at' => isset($article['publishedAt']) ? \Carbon\Carbon::parse($article['publishedAt']) : null,
                        'language' => 'en',
                        'sentiment_status' => 'neutral',
                    ]);
                }

                $this->apiLogRepo->log('GNews API', 'https://gnews.io/api/v4/search', 'GET', 200, true, null, $durationMs);

                return $articles;
            }
        } catch (\Throwable $e) {
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);
            $this->apiLogRepo->log('GNews API', 'https://gnews.io/api/v4/search', 'GET', 500, false, $e->getMessage(), $durationMs);
            Log::error('GNews API Error via NewsApiClient: ' . $e->getMessage());
        }

        // 2. Fallback to cache if network API fails
        $cached = $this->newsCacheRepo->getCache($queryString);
        if ($cached && !empty($cached->news_data)) {
            return $cached->news_data;
        }

        // Fallback response: load database articles
        $localArticles = $this->newsRepo->filter([]);
        return $localArticles->map(function ($item) {
            return [
                'title' => $item->title,
                'description' => $item->description,
                'content' => $item->content,
                'url' => $item->url,
                'image' => $item->image_url,
                'publishedAt' => $item->published_at ? $item->published_at->toIso8601String() : null,
                'source' => ['name' => $item->author ?? $item->source ?? 'Local Intelligence'],
            ];
        })->toArray();
    }

    /**
     * @inheritDoc
     */
    public function searchNews(string $term): Collection
    {
        return $this->newsRepo->search($term);
    }

    /**
     * @inheritDoc
     */
    public function filterNews(array $filters): Collection
    {
        return $this->newsRepo->filter($filters);
    }
}
