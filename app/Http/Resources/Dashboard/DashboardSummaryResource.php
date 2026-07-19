<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class DashboardSummaryResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_countries_monitored' => $this->total_countries_monitored,
            'high_risk_alerts' => $this->high_risk_alerts,
            'recent_incidents' => $this->recent_incidents,
            'last_updated' => $this->last_updated,
        ];
    }
}