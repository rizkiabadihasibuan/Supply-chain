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
            // No business logic here. Delegate to service.
            // $result = $this->SentimentService->analyze(...);
            // return new SentimentResource($result);
            return $this->sendSuccess('Method analyze executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute analyze', [$e->getMessage()], 500);
        }
    }

}