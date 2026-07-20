<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CountryRecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected CountryRecommendationService $service;

    public function __construct(CountryRecommendationService $service)
    {
        $this->service = $service;
    }

    /**
     * GET /api/v1/recommendations
     * Get multi-criteria country import recommendations.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['region', 'max_risk', 'currency', 'min_gdp']);
        $recommendations = $this->service->getRecommendations($filters);

        return response()->json([
            'status' => 'success',
            'message' => 'Rekomendasi negara pemasok impor berhasil diproses',
            'total_items' => $recommendations->count(),
            'data' => $recommendations,
        ]);
    }
}
