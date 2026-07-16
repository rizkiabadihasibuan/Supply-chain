<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryApiController extends Controller
{
    protected CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function index(Request $request): JsonResponse
    {
        // Placeholder enterprise response
        return response()->json([
            'success' => true,
            'message' => 'Country API endpoint ready.',
            'data' => []
        ]);
    }
}
