<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\NewsService;
use App\Http\Resources\News\NewsArticleResource;
use App\Http\Resources\News\NewsCollection;
use App\Http\Requests\News\NewsFilterRequest;
use App\Http\Requests\News\NewsSearchRequest;

class NewsController extends BaseApiController
{
    /**
     * @var NewsService
     */
    protected $NewsService;

    /**
     * Constructor for Dependency Injection
     *
     * @param NewsService $NewsService
     */
    public function __construct(NewsService $NewsService)
    {
        $this->NewsService = $NewsService;
    }

    /**
     * index method
     */
    public function index(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->NewsService->index(...);
            // return new NewsCollection($result);
            return $this->sendSuccess('Method index executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute index', [$e->getMessage()], 500);
        }
    }
    /**
     * show method
     */
    public function show($id)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->NewsService->show(...);
            // return new NewsArticleResource($result);
            return $this->sendSuccess('Method show executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute show', [$e->getMessage()], 500);
        }
    }
    /**
     * filter method
     */
    public function filter(NewsFilterRequest $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->NewsService->filter(...);
            // return new NewsCollection($result);
            return $this->sendSuccess('Method filter executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute filter', [$e->getMessage()], 500);
        }
    }
    /**
     * search method
     */
    public function search(NewsSearchRequest $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->NewsService->search(...);
            // return new NewsCollection($result);
            return $this->sendSuccess('Method search executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute search', [$e->getMessage()], 500);
        }
    }

}