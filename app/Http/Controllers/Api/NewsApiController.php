<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsApiController extends Controller
{
    protected NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'News API endpoint ready.',
            'data' => []
        ]);
    }
}
