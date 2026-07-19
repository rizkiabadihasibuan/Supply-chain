<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\DictionaryService;
use App\Http\Resources\AI\SentimentDictionaryResource;
use App\Http\Requests\AI\DictionaryImportRequest;

class DictionaryController extends BaseApiController
{
    /**
     * @var DictionaryService
     */
    protected $DictionaryService;

    /**
     * Constructor for Dependency Injection
     *
     * @param DictionaryService $DictionaryService
     */
    public function __construct(DictionaryService $DictionaryService)
    {
        $this->DictionaryService = $DictionaryService;
    }

    /**
     * index method
     */
    public function index(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->DictionaryService->index(...);
            // return new SentimentDictionaryResource($result);
            return $this->sendSuccess('Method index executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute index', [$e->getMessage()], 500);
        }
    }
    /**
     * import method
     */
    public function import(DictionaryImportRequest $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->DictionaryService->import(...);
            // return new SentimentDictionaryResource($result);
            return $this->sendSuccess('Method import executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute import', [$e->getMessage()], 500);
        }
    }

}