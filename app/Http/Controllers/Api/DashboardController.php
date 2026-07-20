<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\Contracts\DashboardServiceInterface;

class DashboardController extends BaseApiController
{
    /**
     * @var DashboardServiceInterface
     */
    protected $DashboardService;

    /**
     * Constructor for Dependency Injection
     *
     * @param DashboardServiceInterface $DashboardService
     */
    public function __construct(DashboardServiceInterface $DashboardService)
    {
        $this->DashboardService = $DashboardService;
    }

    /**
     * summary method — Get high-level KPI dashboard metrics
     * GET /api/v1/dashboard
     */
    public function summary(Request $request)
    {
        try {
            $result = $this->DashboardService->getStatsSummary();
            return response()->json([
                'success' => true,
                'message' => 'Dashboard summary retrieved successfully',
                'data' => $result,
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to execute summary', [$e->getMessage()], 500);
        }
    }
}