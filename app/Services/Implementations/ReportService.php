<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\RiskRepositoryInterface;
use App\Repositories\Interfaces\SentimentRepositoryInterface;
use App\Services\Contracts\ReportServiceInterface;

class ReportService implements ReportServiceInterface
{
    /**
     * @var CountryRepositoryInterface
     */
    protected CountryRepositoryInterface $countryRepo;

    /**
     * @var RiskRepositoryInterface
     */
    protected RiskRepositoryInterface $riskRepo;

    /**
     * @var SentimentRepositoryInterface
     */
    protected SentimentRepositoryInterface $sentimentRepo;

    /**
     * ReportService constructor.
     *
     * @param CountryRepositoryInterface $countryRepo
     * @param RiskRepositoryInterface $riskRepo
     * @param SentimentRepositoryInterface $sentimentRepo
     */
    public function __construct(
        CountryRepositoryInterface $countryRepo,
        RiskRepositoryInterface $riskRepo,
        SentimentRepositoryInterface $sentimentRepo
    ) {
        $this->countryRepo = $countryRepo;
        $this->riskRepo = $riskRepo;
        $this->sentimentRepo = $sentimentRepo;
    }

    /**
     * @inheritDoc
     */
    public function generateReport(int $countryId): array
    {
        $country = $this->countryRepo->findById($countryId);
        $riskScore = $this->riskRepo->findByCountry($countryId);
        $sentimentResult = $this->sentimentRepo->filter(['country_id' => $countryId])->first();

        return [
            'country' => $country,
            'risk_score' => $riskScore,
            'components' => $riskScore ? $riskScore->components()->get() : [],
            'sentiment' => $sentimentResult,
            'generated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ];
    }
}
