<?php

declare(strict_types=1);

namespace App\Services\Contracts;

interface DashboardServiceInterface
{
    /**
     * Get aggregate statistics summary for dashboards.
     *
     * @return array<string, mixed>
     */
    public function getStatsSummary(): array;
}
