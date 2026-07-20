<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\SentimentService;
use App\Http\Resources\AI\SentimentResource;
use App\Http\Requests\AI\SentimentAnalysisRequest;

class SentimentController extends BaseApiController
{
    /**
     * @var SentimentService
     */
    protected $SentimentService;

    /**
     * Constructor for Dependency Injection
     *
     * @param SentimentService $SentimentService
     */
    public function __construct(SentimentService $SentimentService)
    {
        $this->SentimentService = $SentimentService;
    }

    /**
     * analyze method
     */
    public function analyze(Request $request)
    {
        try {
            $articleId = $request->input('news_article_id');
            $dictionaryId = $request->input('dictionary_id', 1);

            if (!$articleId) {
                return $this->sendError('Validation Error', ['news_article_id is required.'], 400);
            }

            $result = $this->SentimentService->analyzeArticle((int) $articleId, (int) $dictionaryId);

            return response()->json([
                'success' => true,
                'message' => 'Sentiment analyzed successfully',
                'data' => $result
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to execute analyze', [$e->getMessage()], 500);
        }
    }

}