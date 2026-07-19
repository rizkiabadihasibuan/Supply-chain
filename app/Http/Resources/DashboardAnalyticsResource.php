<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;

class DashboardAnalyticsResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = is_array($this->resource) ? $this->resource : $this->resource->toArray();

        return [
            'type' => $data['type'] ?? '',
            'data' => $data['data'] ?? [],
            'generated_at' => $data['generated_at'] ?? now()->toIso8601String(),
        ];
    }
}
