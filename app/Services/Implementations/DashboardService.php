<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Repositories\Interfaces\PortRepositoryInterface;
use App\Repositories\Interfaces\RiskRepositoryInterface;
use App\Services\Contracts\DashboardServiceInterface;

class DashboardService implements DashboardServiceInterface
{
    /**
     * @var CountryRepositoryInterface
     */
    protected CountryRepositoryInterface $countryRepo;

    /**
     * @var PortRepositoryInterface
     */
    protected PortRepositoryInterface $portRepo;

    /**
     * @var RiskRepositoryInterface
     */
    protected RiskRepositoryInterface $riskRepo;

    /**
     * @var NewsRepositoryInterface
     */
    protected NewsRepositoryInterface $newsRepo;

    /**
     * DashboardService constructor.
     *
     * @param CountryRepositoryInterface $countryRepo
     * @param PortRepositoryInterface $portRepo
     * @param RiskRepositoryInterface $riskRepo
     * @param NewsRepositoryInterface $newsRepo
     */
    public function __construct(
        CountryRepositoryInterface $countryRepo,
        PortRepositoryInterface $portRepo,
        RiskRepositoryInterface $riskRepo,
        NewsRepositoryInterface $newsRepo
    ) {
        $this->countryRepo = $countryRepo;
        $this->portRepo = $portRepo;
        $this->riskRepo = $riskRepo;
        $this->newsRepo = $newsRepo;
    }

    /**
     * @inheritDoc
     */
    public function getStatsSummary(): array
    {
        return [
            'total_countries' => $this->countryRepo->findAll()->count(),
            'total_ports' => $this->portRepo->findAll()->count(),
            'critical_risks' => $this->riskRepo->getCriticalRisks()->count(),
            'news_articles_count' => $this->newsRepo->findAll()->count(),
        ];
    }
}
