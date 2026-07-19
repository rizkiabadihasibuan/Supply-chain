<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\AnalyticsService;
use App\Http\Resources\Dashboard\AnalyticsResource;
use App\Http\Requests\Dashboard\AnalyticsFilterRequest;

class AnalyticsController extends BaseApiController
{
    /**
     * @var AnalyticsService
     */
    protected $AnalyticsService;

    /**
     * Constructor for Dependency Injection
     *
     * @param AnalyticsService $AnalyticsService
     */
    public function __construct(AnalyticsService $AnalyticsService)
    {
        $this->AnalyticsService = $AnalyticsService;
    }

    /**
     * index method
     */
    public function index(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->AnalyticsService->index(...);
            // return new AnalyticsResource($result);
            return $this->sendSuccess('Method index executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute index', [$e->getMessage()], 500);
        }
    }

}