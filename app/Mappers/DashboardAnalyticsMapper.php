<?php

declare(strict_types=1);

namespace App\Mappers;

use App\DTOs\DashboardAnalyticsDTO;

class DashboardAnalyticsMapper
{
    /**
     * Map processed analytics data into a DashboardAnalyticsDTO.
     */
    public function map(string $type, array $data): DashboardAnalyticsDTO
    {
        return new DashboardAnalyticsDTO($type, $data);
    }
}
