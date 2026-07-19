<?php

namespace App\Http\Resources\Shared;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class ApiErrorResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => false,
            'message' => $this->resource['message'] ?? 'Error occurred',
            'errors' => $this->resource['errors'] ?? null,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}