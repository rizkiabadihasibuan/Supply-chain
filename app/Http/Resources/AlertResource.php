<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AlertResource extends BaseResource
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
            'alert_id' => $data['id'] ?? null,
            'country' => $data['countryName'] ?? '',
            'alert_type' => $data['alertType'] ?? '',
            'severity' => $data['severity'] ?? 'Medium',
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'triggered_by' => $data['triggeredBy'] ?? '',
            'current_score' => $data['currentScore'] ?? 0.0,
            'previous_score' => $data['previousScore'] ?? 0.0,
            'trend' => $data['trend'] ?? 'Stable',
            'created_at' => $data['createdAt'] ?? now()->toIso8601String(),
            'status' => $data['status'] ?? 'New',
        ];
    }
}
