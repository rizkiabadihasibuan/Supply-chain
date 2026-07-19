<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Repositories\Interfaces\RiskRepositoryInterface;
use App\Services\Contracts\AnalyticsServiceInterface;

class AnalyticsService implements AnalyticsServiceInterface
{
    /**
     * @var RiskRepositoryInterface
     */
    protected RiskRepositoryInterface $riskRepo;

    /**
     * AnalyticsService constructor.
     *
     * @param RiskRepositoryInterface $riskRepo
     */
    public function __construct(RiskRepositoryInterface $riskRepo)
    {
        $this->riskRepo = $riskRepo;
    }

    /**
     * @inheritDoc
     */
    public function getAnalyticsCharts(): array
    {
        $scores = $this->riskRepo->findAll();

        $distribution = [
            'Very Low' => 0,
            'Low' => 0,
            'Medium' => 0,
            'High' => 0,
            'Critical' => 0,
        ];

        foreach ($scores as $score) {
            $level = $score->risk_level;
            if (isset($distribution[$level])) {
                $distribution[$level]++;
            }
        }

        return [
            'risk_distribution' => $distribution,
            'total_scored' => $scores->count(),
        ];
    }
}
