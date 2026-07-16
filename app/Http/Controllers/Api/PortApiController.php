<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PortService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortApiController extends Controller
{
    protected PortService $portService;

    public function __construct(PortService $portService)
    {
        $this->portService = $portService;
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Port API endpoint ready.',
            'data' => []
        ]);
    }
}
