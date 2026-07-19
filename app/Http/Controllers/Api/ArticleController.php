<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\ArticleService;
use App\Http\Resources\News\NewsArticleResource;
use App\Http\Requests\News\StoreNewsRequest;
use App\Http\Requests\News\UpdateNewsRequest;

class ArticleController extends BaseApiController
{
    /**
     * @var ArticleService
     */
    protected $ArticleService;

    /**
     * Constructor for Dependency Injection
     *
     * @param ArticleService $ArticleService
     */
    public function __construct(ArticleService $ArticleService)
    {
        $this->ArticleService = $ArticleService;
    }

    /**
     * store method
     */
    public function store(StoreNewsRequest $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->ArticleService->store(...);
            // return new NewsArticleResource($result);
            return $this->sendSuccess('Method store executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute store', [$e->getMessage()], 500);
        }
    }
    /**
     * update method
     */
    public function update(UpdateNewsRequest $request, $id)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->ArticleService->update(...);
            // return new NewsArticleResource($result);
            return $this->sendSuccess('Method update executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute update', [$e->getMessage()], 500);
        }
    }
    /**
     * destroy method
     */
    public function destroy($id)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->ArticleService->destroy(...);
            // return new NewsArticleResource($result);
            return $this->sendSuccess('Method destroy executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute destroy', [$e->getMessage()], 500);
        }
    }

}