<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shared\ApiErrorResource;
use App\Http\Resources\Shared\ApiSuccessResource;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    /**
     * Send error response
     */
    protected function sendError($message, $errors = [], $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toIso8601String()
        ], $code);
    }
    
    /**
     * Send success response
     */
    protected function sendSuccess($message, $data = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toIso8601String()
        ], 200);
    }
}