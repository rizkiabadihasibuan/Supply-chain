<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\RiskHistoryService;
use App\Http\Resources\Risk\RiskHistoryResource;

class RiskHistoryController extends BaseApiController
{
    /**
     * @var RiskHistoryService
     */
    protected $RiskHistoryService;

    /**
     * Constructor for Dependency Injection
     *
     * @param RiskHistoryService $RiskHistoryService
     */
    public function __construct(RiskHistoryService $RiskHistoryService)
    {
        $this->RiskHistoryService = $RiskHistoryService;
    }

    /**
     * index method
     */
    public function index(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->RiskHistoryService->index(...);
            // return new RiskHistoryResource($result);
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
            // $result = $this->RiskHistoryService->show(...);
            // return new RiskHistoryResource($result);
            return $this->sendSuccess('Method show executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute show', [$e->getMessage()], 500);
        }
    }

}