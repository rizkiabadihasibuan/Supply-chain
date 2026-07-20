<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Services\Contracts\CountryServiceInterface;
use App\Services\Contracts\WeatherServiceInterface;
use App\Services\Contracts\CurrencyServiceInterface;
use App\Services\Contracts\NewsServiceInterface;
use App\Services\Contracts\PortServiceInterface;
use App\Services\Contracts\RiskServiceInterface;
use App\Services\Contracts\DashboardServiceInterface;

class DashboardService implements DashboardServiceInterface
{
    protected CountryServiceInterface $countryService;
    protected WeatherServiceInterface $weatherService;
    protected CurrencyServiceInterface $currencyService;
    protected NewsServiceInterface $newsService;
    protected PortServiceInterface $portService;
    protected RiskServiceInterface $riskService;

    public function __construct(
        CountryServiceInterface $countryService,
        WeatherServiceInterface $weatherService,
        CurrencyServiceInterface $currencyService,
        NewsServiceInterface $newsService,
        PortServiceInterface $portService,
        RiskServiceInterface $riskService
    ) {
        $this->countryService = $countryService;
        $this->weatherService = $weatherService;
        $this->currencyService = $currencyService;
        $this->newsService = $newsService;
        $this->portService = $portService;
        $this->riskService = $riskService;
    }

    /**
     * @inheritDoc
     */
    public function getStatsSummary(): array
    {
        $allRisks = $this->riskService->filterRiskScores([]);
        $avgRisk = $allRisks->count() > 0 ? (float) $allRisks->avg('final_risk_score') : 0.0;

        return [
            'total_countries' => $this->countryService->getCountries()->count(),
            'total_ports' => $this->portService->getPorts()->count(),
            'critical_risks' => $this->riskService->getCriticalCountryRisks()->count(),
            'news_articles_count' => $this->newsService->filterNews([])->count(),
            'global_average_risk_score' => round($avgRisk, 2),
        ];
    }
}
