<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Services\DashboardAnalyticsService;
use App\Http\Resources\DashboardAnalyticsResource;
use App\Exceptions\MissingRiskDataException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardAnalyticsController extends BaseApiController
{
    protected DashboardAnalyticsService $service;

    public function __construct(DashboardAnalyticsService $service)
    {
        $this->service = $service;
    }

    /**
     * Parse and validate request filters.
     */
    protected function getFilters(Request $request): array
    {
        $request->validate([
            'country_id' => 'nullable|integer|exists:countries,id',
            'region_id' => 'nullable|integer|exists:regions,id',
            'subregion' => 'nullable|string|max:255',
            'continent' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'classification_id' => 'nullable|integer|exists:risk_classifications,id',
        ]);

        return array_filter($request->only([
            'country_id',
            'region_id',
            'subregion',
            'continent',
            'start_date',
            'end_date',
            'classification_id',
        ]), fn($v) => !is_null($v) && $v !== '');
    }

    /**
     * Handle controller action exceptions.
     */
    protected function handleException(\Throwable $e, string $message): JsonResponse
    {
        if ($e instanceof MissingRiskDataException) {
            return $this->sendError($e->getMessage(), [], 422);
        }
        return $this->sendError($message, [$e->getMessage()], 500);
    }

    /**
     * 1. Overview
     */
    public function overview(Request $request): JsonResponse
    {
        try {
            $filters = $this->getFilters($request);
            $dto = $this->service->getOverview($filters);
            return $this->sendSuccess('Overview analytics retrieved successfully', new DashboardAnalyticsResource($dto));
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve overview analytics.');
        }
    }

    /**
     * 2. Global Summary
     */
    public function globalSummary(Request $request): JsonResponse
    {
        try {
            $filters = $this->getFilters($request);
            $dto = $this->service->getGlobalSummary($filters);
            return $this->sendSuccess('Global summary analytics retrieved successfully', new DashboardAnalyticsResource($dto));
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve global summary.');
        }
    }

    /**
     * 3. Risk Distribution
     */
    public function riskDistribution(Request $request): JsonResponse
    {
        try {
            $filters = $this->getFilters($request);
            $dto = $this->service->getRiskDistribution($filters);
            return $this->sendSuccess('Risk distribution analytics retrieved successfully', new DashboardAnalyticsResource($dto));
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve risk distribution.');
        }
    }

    /**
     * 4. Top Risk Countries
     */
    public function topRiskCountries(Request $request): JsonResponse
    {
        try {
            $filters = $this->getFilters($request);
            $dto = $this->service->getTopRiskCountries($filters);
            return $this->sendSuccess('Top risk countries retrieved successfully', new DashboardAnalyticsResource($dto));
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve top risk countries.');
        }
    }

    /**
     * 5. Lowest Risk Countries
     */
    public function lowestRiskCountries(Request $request): JsonResponse
    {
        try {
            $filters = $this->getFilters($request);
            $dto = $this->service->getLowestRiskCountries($filters);
            return $this->sendSuccess('Lowest risk countries retrieved successfully', new DashboardAnalyticsResource($dto));
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve lowest risk countries.');
        }
    }

    /**
     * 6. Risk Trends
     */
    public function riskTrends(Request $request): JsonResponse
    {
        try {
            $filters = $this->getFilters($request);
            $dto = $this->service->getRiskTrends($filters);
            return $this->sendSuccess('Risk trends retrieved successfully', new DashboardAnalyticsResource($dto));
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve risk trends.');
        }
    }

    /**
     * 7. Risk Rankings
     */
    public function riskRanking(Request $request): JsonResponse
    {
        try {
            $filters = $this->getFilters($request);
            $dto = $this->service->getRiskRanking($filters);
            return $this->sendSuccess('Risk rankings retrieved successfully', new DashboardAnalyticsResource($dto));
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve risk rankings.');
        }
    }

    /**
     * 8. Alerts Summary
     */
    public function alertsSummary(Request $request): JsonResponse
    {
        try {
            $filters = $this->getFilters($request);
            $dto = $this->service->getAlertsSummary($filters);
            return $this->sendSuccess('Alerts summary retrieved successfully', new DashboardAnalyticsResource($dto));
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve alerts summary.');
        }
    }

    /**
     * 9. Weather Risk
     */
    public function weatherRisk(Request $request): JsonResponse
    {
        try {
            $filters = $this->getFilters($request);
            $dto = $this->service->getWeatherRisk($filters);
            return $this->sendSuccess('Weather risk analytics retrieved successfully', new DashboardAnalyticsResource($dto));
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve weather risk analytics.');
        }
    }

    /**
     * 10. Economic Risk
     */
    public function economicRisk(Request $request): JsonResponse
    {
        try {
            $filters = $this->getFilters($request);
            $dto = $this->service->getEconomicRisk($filters);
            return $this->sendSuccess('Economic risk analytics retrieved successfully', new DashboardAnalyticsResource($dto));
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve economic risk analytics.');
        }
    }

    /**
     * 11. Political Risk
     */
    public function politicalRisk(Request $request): JsonResponse
    {
        try {
            $filters = $this->getFilters($request);
            $dto = $this->service->getPoliticalRisk($filters);
            return $this->sendSuccess('Political risk analytics retrieved successfully', new DashboardAnalyticsResource($dto));
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve political risk analytics.');
        }
    }

    /**
     * 12. Logistics Risk
     */
    public function logisticsRisk(Request $request): JsonResponse
    {
        try {
            $filters = $this->getFilters($request);
            $dto = $this->service->getLogisticsRisk($filters);
            return $this->sendSuccess('Logistics risk analytics retrieved successfully', new DashboardAnalyticsResource($dto));
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve logistics risk analytics.');
        }
    }
}
