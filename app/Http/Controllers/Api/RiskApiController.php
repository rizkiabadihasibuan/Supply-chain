<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RiskScoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RiskApiController extends Controller
{
    protected RiskScoringService $riskScoringService;

    public function __construct(RiskScoringService $riskScoringService)
    {
        $this->riskScoringService = $riskScoringService;
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Risk Scoring API endpoint ready.',
            'data' => []
        ]);
    }
}
