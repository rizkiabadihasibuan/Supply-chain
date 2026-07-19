<?php

declare(strict_types=1);

namespace App\Services\Contracts;

interface AnalyticsServiceInterface
{
    /**
     * Get aggregate analytical charts data.
     *
     * @return array<string, mixed>
     */
    public function getAnalyticsCharts(): array;
}
