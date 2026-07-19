<?php

declare(strict_types=1);

namespace App\Services\Contracts;

interface ReportServiceInterface
{
    /**
     * Generate risk intelligence report data.
     *
     * @param int $countryId
     * @return array<string, mixed>
     */
    public function generateReport(int $countryId): array;
}
