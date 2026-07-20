<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\Contracts\RiskServiceInterface;
use App\Models\Country;
use App\Models\RiskScore;
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
     * index method — Get all risk scores with country data
     * GET /api/v1/risk
     */
    public function index(Request $request)
    {
        try {
            $query = RiskScore::with(['country.region', 'country.currency', 'classification']);

            // Optional filter by risk level
            if ($request->has('risk_level')) {
                $query->where('risk_level', $request->query('risk_level'));
            }

            // Optional filter by region
            if ($request->has('region_id')) {
                $query->whereHas('country', fn($q) => $q->where('region_id', $request->query('region_id')));
            }

            $riskScores = $query->orderBy('final_risk_score', 'desc')->get();

            $result = $riskScores->map(function ($score) {
                return [
                    'id' => $score->id,
                    'country' => [
                        'id' => $score->country->id,
                        'name' => $score->country->name,
                        'code' => $score->country->code,
                        'flag_url' => $score->country->flag_url,
                        'region' => $score->country->region?->name,
                        'currency' => $score->country->currency?->code,
                    ],
                    'final_risk_score' => round($score->final_risk_score, 2),
                    'risk_level' => $score->risk_level,
                    'risk_color' => $score->risk_color,
                    'components' => [
                        'weather' => $score->weather_score,
                        'inflation' => $score->inflation_score,
                        'currency' => $score->currency_score,
                        'political' => $score->political_score,
                    ],
                    'classification' => $score->classification ? [
                        'name' => $score->classification->name,
                        'color_code' => $score->classification->color_code,
                        'description' => $score->classification->description,
                    ] : null,
                    'updated_at' => $score->updated_at?->toIso8601String(),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Risk scores retrieved successfully',
                'data' => $result,
                'total' => $result->count(),
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve risk scores', [$e->getMessage()], 500);
        }
    }

    /**
     * show method — Get risk score for a specific country
     * GET /api/v1/risk/{country}
     */
    public function show($id)
    {
        try {
            // Find country by ID, code, or name
            $country = null;
            if (is_numeric($id)) {
                $country = Country::with(['riskScore.classification', 'region', 'currency'])->find((int) $id);
            } else {
                $country = Country::with(['riskScore.classification', 'region', 'currency'])
                    ->where('code', strtoupper($id))
                    ->orWhere('name', 'like', '%' . $id . '%')
                    ->first();
            }

            if (!$country) {
                return $this->sendError('Country not found', [], 404);
            }

            $score = $country->riskScore;

            $result = [
                'country' => [
                    'id' => $country->id,
                    'name' => $country->name,
                    'code' => $country->code,
                    'flag_url' => $country->flag_url,
                    'region' => $country->region?->name,
                    'currency' => $country->currency?->code,
                    'population' => $country->population,
                    'latitude' => $country->latitude,
                    'longitude' => $country->longitude,
                ],
                'risk' => $score ? [
                    'final_risk_score' => round($score->final_risk_score, 2),
                    'risk_level' => $score->risk_level,
                    'risk_color' => $score->risk_color,
                    'components' => [
                        'weather' => ['score' => $score->weather_score, 'weight' => 0.30, 'weighted' => round($score->weather_score * 0.30, 2)],
                        'inflation' => ['score' => $score->inflation_score, 'weight' => 0.20, 'weighted' => round($score->inflation_score * 0.20, 2)],
                        'political' => ['score' => $score->political_score, 'weight' => 0.40, 'weighted' => round($score->political_score * 0.40, 2)],
                        'currency' => ['score' => $score->currency_score, 'weight' => 0.10, 'weighted' => round($score->currency_score * 0.10, 2)],
                    ],
                    'classification' => $score->classification ? [
                        'name' => $score->classification->name,
                        'color_code' => $score->classification->color_code,
                        'description' => $score->classification->description,
                    ] : null,
                    'updated_at' => $score->updated_at?->toIso8601String(),
                ] : [
                    'final_risk_score' => 0,
                    'risk_level' => 'Not Calculated',
                    'message' => 'Risk score belum dihitung untuk negara ini.',
                ],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Risk score retrieved successfully',
                'data' => $result,
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve risk score', [$e->getMessage()], 500);
        }
    }

    /**
     * store method — Calculate and save risk score for a country
     * POST /api/v1/risk/calculate
     *
     * Uses Weighted Risk Model:
     * Weather Risk = 30%, Inflation Risk = 20%, Political/News = 40%, Currency = 10%
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'country_id' => 'required|integer|exists:countries,id',
                'weather_score' => 'nullable|numeric|min:0|max:100',
                'inflation_score' => 'nullable|numeric|min:0|max:100',
                'political_score' => 'nullable|numeric|min:0|max:100',
                'currency_score' => 'nullable|numeric|min:0|max:100',
            ]);

            $countryId = $request->input('country_id');
            $weatherScore = (float) $request->input('weather_score', 0);
            $inflationScore = (float) $request->input('inflation_score', 0);
            $politicalScore = (float) $request->input('political_score', 0);
            $currencyScore = (float) $request->input('currency_score', 0);

            // Weighted Risk Model calculation
            $weatherWeighted = $weatherScore * 0.30;
            $inflationWeighted = $inflationScore * 0.20;
            $politicalWeighted = $politicalScore * 0.40;
            $currencyWeighted = $currencyScore * 0.10;

            $finalScore = $weatherWeighted + $inflationWeighted + $politicalWeighted + $currencyWeighted;
            $finalScore = max(0.00, min(100.00, $finalScore));

            // Determine risk level
            if ($finalScore <= 20) $riskLevel = 'Very Low';
            elseif ($finalScore <= 40) $riskLevel = 'Low';
            elseif ($finalScore <= 60) $riskLevel = 'Medium';
            elseif ($finalScore <= 80) $riskLevel = 'High';
            else $riskLevel = 'Critical';

            // Save or update risk score
            $riskScore = RiskScore::updateOrCreate(
                ['country_id' => $countryId],
                [
                    'final_risk_score' => round($finalScore, 2),
                    'risk_level' => $riskLevel,
                    'components' => [
                        'weather' => $weatherScore,
                        'inflation' => $inflationScore,
                        'political' => $politicalScore,
                        'currency' => $currencyScore,
                    ],
                ]
            );

            $riskScore->load(['country', 'classification']);

            return response()->json([
                'success' => true,
                'message' => 'Risk score calculated and saved successfully',
                'data' => [
                    'country' => $riskScore->country->name,
                    'final_risk_score' => round($finalScore, 2),
                    'risk_level' => $riskLevel,
                    'components' => [
                        'weather' => ['raw' => $weatherScore, 'weight' => '30%', 'weighted' => round($weatherWeighted, 2)],
                        'inflation' => ['raw' => $inflationScore, 'weight' => '20%', 'weighted' => round($inflationWeighted, 2)],
                        'political' => ['raw' => $politicalScore, 'weight' => '40%', 'weighted' => round($politicalWeighted, 2)],
                        'currency' => ['raw' => $currencyScore, 'weight' => '10%', 'weighted' => round($currencyWeighted, 2)],
                    ],
                ],
            ], 201);
        } catch (Exception $e) {
            return $this->sendError('Failed to calculate risk score', [$e->getMessage()], 500);
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