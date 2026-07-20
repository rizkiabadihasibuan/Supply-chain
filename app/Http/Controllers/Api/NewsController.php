<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\Contracts\NewsServiceInterface;
use App\Models\NewsArticle;
use App\Http\Resources\News\NewsArticleResource;
use App\Http\Resources\News\NewsCollection;
use App\Http\Requests\News\NewsFilterRequest;
use App\Http\Requests\News\NewsSearchRequest;

class NewsController extends BaseApiController
{
    /**
     * @var NewsServiceInterface
     */
    protected $NewsService;

    /**
     * Constructor for Dependency Injection
     *
     * @param NewsServiceInterface $NewsService
     */
    public function __construct(NewsServiceInterface $NewsService)
    {
        $this->NewsService = $NewsService;
    }

    /**
     * index method — Get all news articles
     * GET /api/v1/news
     */
    public function index(Request $request)
    {
        try {
            // Try to get from database first
            $articles = $this->NewsService->filterNews($request->all());

            // If database is empty, try to auto-fetch from GNews API
            if ($articles->isEmpty()) {
                $query = $request->input('q', 'supply chain logistics trade');
                $this->NewsService->fetchNews($query);
                $articles = $this->NewsService->searchNews('');
            }

            return response()->json([
                'success' => true,
                'message' => 'News articles retrieved successfully',
                'data' => NewsArticleResource::collection($articles),
                'total' => $articles->count(),
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve news articles', [$e->getMessage()], 500);
        }
    }

    /**
     * show method — Get single news article by ID
     * GET /api/v1/news/{id}
     */
    public function show($id)
    {
        try {
            $article = NewsArticle::find($id);

            if (!$article) {
                return $this->sendError('News article not found', [], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'News article retrieved successfully',
                'data' => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'description' => $article->description,
                    'content' => $article->content,
                    'url' => $article->url,
                    'image_url' => $article->image_url,
                    'author' => $article->author,
                    'source' => $article->source,
                    'language' => $article->language,
                    'sentiment_status' => $article->sentiment_status,
                    'published_at' => $article->published_at?->toIso8601String(),
                    'created_at' => $article->created_at?->toIso8601String(),
                ],
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve news article', [$e->getMessage()], 500);
        }
    }

    /**
     * search method — Search news articles by keyword
     * GET /api/v1/news/search?q=logistics
     */
    public function search(Request $request)
    {
        try {
            $keyword = $request->input('q', $request->input('query', ''));

            // Search in local database first
            $articles = $this->NewsService->searchNews($keyword);

            // If no results found locally, try to fetch from GNews API
            if ($articles->isEmpty() && !empty($keyword)) {
                $this->NewsService->fetchNews($keyword);
                $articles = $this->NewsService->searchNews($keyword);
            }

            return response()->json([
                'success' => true,
                'message' => 'News search results retrieved successfully',
                'data' => NewsArticleResource::collection($articles),
                'total' => $articles->count(),
                'query' => $keyword,
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to search news', [$e->getMessage()], 500);
        }
    }

}