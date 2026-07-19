<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface DashboardAnalyticsRepositoryInterface
{
    /**
     * Get aggregate overview data.
     */
    public function getOverviewData(array $filters): array;

    /**
     * Get global summary statistical data.
     */
    public function getGlobalSummaryData(array $filters): array;

    /**
     * Get count of countries per risk classification.
     */
    public function getRiskDistributionData(array $filters): array;

    /**
     * Get top high risk countries.
     */
    public function getTopRiskCountriesData(array $filters): array;

    /**
     * Get top lowest risk countries.
     */
    public function getLowestRiskCountriesData(array $filters): array;

    /**
     * Get top trending up/down countries.
     */
    public function getRiskTrendsData(array $filters): array;

    /**
     * Get all country risk rankings.
     */
    public function getRiskRankingData(array $filters): array;

    /**
     * Get alert stats and category counts.
     */
    public function getAlertsSummaryData(array $filters): array;

    /**
     * Get stats for a specific component risk (weather, economic, political, logistics).
     */
    public function getComponentRiskData(string $component, array $filters): array;
}
