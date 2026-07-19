<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\NewsArticle;
use App\Repositories\Interfaces\ApiLogRepositoryInterface;
use App\Repositories\Interfaces\NewsCacheRepositoryInterface;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Services\Contracts\NewsServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsService implements NewsServiceInterface
{
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
     *
     * @param NewsRepositoryInterface $newsRepo
     * @param NewsCacheRepositoryInterface $newsCacheRepo
     * @param ApiLogRepositoryInterface $apiLogRepo
     */
    public function __construct(
        NewsRepositoryInterface $newsRepo,
        NewsCacheRepositoryInterface $newsCacheRepo,
        ApiLogRepositoryInterface $apiLogRepo
    ) {
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

        if (!$forceRefresh) {
            $cached = $this->newsCacheRepo->getCache($queryString);
            if ($cached) {
                return $cached->news_data;
            }
        }

        $endpoint = 'https://gnews.io/api/v4/search';
        $apiKey = config('services.gnews.key') ?? env('GNEWS_API_KEY', 'placeholder_key');
        $params = [
            'q' => $queryString,
            'lang' => 'en',
            'apikey' => $apiKey,
        ];

        $startTime = microtime(true);
        $statusCode = null;
        $isSuccess = false;
        $errorMessage = null;

        try {
            $response = Http::timeout(12)->get($endpoint, $params);
            $statusCode = $response->status();
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);

            if ($response->successful()) {
                $isSuccess = true;
                $data = $response->json();
                $articles = $data['articles'] ?? [];

                $this->newsCacheRepo->saveCache($queryString, $articles);

                // Save individual articles to database for local reference and sentiment NLP matching
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
                        'sentiment_status' => 'neutral', // default initial state
                    ]);
                }

                $this->apiLogRepo->log('GNews API', $endpoint, 'GET', $statusCode, true, null, $durationMs);

                return $articles;
            }

            $errorMessage = $response->body();
            $this->apiLogRepo->log('GNews API', $endpoint, 'GET', $statusCode, false, $errorMessage, $durationMs);
        } catch (\Exception $e) {
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);
            $errorMessage = $e->getMessage();
            $this->apiLogRepo->log('GNews API', $endpoint, 'GET', $statusCode, false, $errorMessage, $durationMs);
            Log::error('GNews API Error: ' . $errorMessage);
        }

        return [];
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
