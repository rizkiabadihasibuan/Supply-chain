<?php

namespace App\Http\Resources\Shared;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class ApiSuccessResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'message' => $this->resource['message'] ?? 'Success',
            'data' => $this->resource['data'] ?? [],
            'timestamp' => now()->toIso8601String(),
        ];
    }
}