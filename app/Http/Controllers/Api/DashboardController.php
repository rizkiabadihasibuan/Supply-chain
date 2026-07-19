<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\DashboardService;
use App\Http\Resources\Dashboard\DashboardSummaryResource;
use App\Http\Requests\Dashboard\DashboardFilterRequest;

class DashboardController extends BaseApiController
{
    /**
     * @var DashboardService
     */
    protected $DashboardService;

    /**
     * Constructor for Dependency Injection
     *
     * @param DashboardService $DashboardService
     */
    public function __construct(DashboardService $DashboardService)
    {
        $this->DashboardService = $DashboardService;
    }

    /**
     * summary method
     */
    public function summary(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->DashboardService->summary(...);
            // return new DashboardSummaryResource($result);
            return $this->sendSuccess('Method summary executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute summary', [$e->getMessage()], 500);
        }
    }

}