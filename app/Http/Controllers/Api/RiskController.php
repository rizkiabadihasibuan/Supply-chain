<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\Contracts\RiskServiceInterface;
use App\Http\Resources\Risk\RiskScoreResource;
use App\Http\Requests\Risk\RiskCalculationRequest;
use App\Http\Requests\Risk\RiskFilterRequest;
use App\Http\Requests\Risk\RiskComparisonRequest;

class RiskController extends BaseApiController
{
    /**
     * @var RiskServiceInterface
     */
    protected $RiskService;

    /**
     * Constructor for Dependency Injection
     *
     * @param RiskServiceInterface $RiskService
     */
    public function __construct(RiskServiceInterface $RiskService)
    {
        $this->RiskService = $RiskService;
    }

    /**
     * index method
     */
    public function index(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->RiskService->index(...);
            // return new RiskScoreResource($result);
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
            // $result = $this->RiskService->show(...);
            // return new RiskScoreResource($result);
            return $this->sendSuccess('Method show executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute show', [$e->getMessage()], 500);
        }
    }
    /**
     * filter method
     */
    public function filter(RiskFilterRequest $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->RiskService->filter(...);
            // return new RiskScoreResource($result);
            return $this->sendSuccess('Method filter executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute filter', [$e->getMessage()], 500);
        }
    }
    /**
     * compare method
     */
    public function compare(RiskComparisonRequest $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->RiskService->compare(...);
            // return new RiskScoreResource($result);
            return $this->sendSuccess('Method compare executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute compare', [$e->getMessage()], 500);
        }
    }

    /**
     * trend method
     */
    public function trend(string $id, \App\Services\RiskTrendService $trendService)
    {
        try {
            $country = is_numeric($id) 
                ? app(\App\Services\CountryService::class)->getCountryById((int) $id)
                : app(\App\Services\CountryService::class)->getCountryByCode($id);

            if (!$country) {
                return $this->sendError('Country not found', [], 404);
            }

            $dto = $trendService->analyzeForCountry($country);

            return new \App\Http\Resources\Risk\RiskTrendResource($dto);
        } catch (\App\Exceptions\RiskHistoryNotFoundException $e) {
            return $this->sendError($e->getMessage(), [], 404);
        } catch (\App\Exceptions\IncompleteRiskDataException $e) {
            return $this->sendError($e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            return $this->sendError('Failed to execute trend analysis', [$e->getMessage()], 500);
        }
    }

    /**
     * alerts method
     */
    public function alerts(string $id, \App\Services\AlertEngineService $alertService)
    {
        try {
            $country = is_numeric($id) 
                ? app(\App\Services\CountryService::class)->getCountryById((int) $id)
                : app(\App\Services\CountryService::class)->getCountryByCode($id);

            if (!$country) {
                return $this->sendError('Country not found', [], 404);
            }

            // Generate/evaluate rules and then remember/fetch active ones
            $alertService->generateAlertsForCountry($country);
            $dtos = $alertService->remember($country);

            return $this->sendSuccess('Alerts retrieved successfully', \App\Http\Resources\AlertResource::collection($dtos)->resolve());
        } catch (\App\Exceptions\MissingRiskDataException $e) {
            return $this->sendError($e->getMessage(), [], 422);
        } catch (\App\Exceptions\AlertRuleConfigurationException $e) {
            return $this->sendError($e->getMessage(), [], 500);
        } catch (\Throwable $e) {
            return $this->sendError('Failed to execute alert evaluation', [$e->getMessage()], 500);
        }
    }

}