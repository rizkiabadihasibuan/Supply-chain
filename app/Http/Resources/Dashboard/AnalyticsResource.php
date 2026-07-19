<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class AnalyticsResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'metric' => $this->metric,
            'data_points' => $this->data_points,
            'trend' => $this->whenNotNull($this->trend),
        ];
    }
}