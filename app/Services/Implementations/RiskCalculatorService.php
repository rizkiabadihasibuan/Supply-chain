<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\RiskClassification;
use App\Models\RiskScore;
use App\Repositories\Interfaces\RiskRepositoryInterface;
use App\Services\Contracts\RiskCalculatorServiceInterface;
use Illuminate\Support\Facades\Log;

class RiskCalculatorService implements RiskCalculatorServiceInterface
{
    /**
     * @var RiskRepositoryInterface
     */
    protected RiskRepositoryInterface $riskRepo;

    /**
     * RiskCalculatorService constructor.
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
    public function calculateCountryRisk(
        int $countryId,
        float $weatherRaw,
        float $inflationRaw,
        float $politicalRaw,
        float $currencyRaw
    ): RiskScore {
        // Calculate weighted score
        $weatherWeighted = $weatherRaw * 0.30;
        $inflationWeighted = $inflationRaw * 0.20;
        $politicalWeighted = $politicalRaw * 0.40;
        $currencyWeighted = $currencyRaw * 0.10;

        $finalScore = $weatherWeighted + $inflationWeighted + $politicalWeighted + $currencyWeighted;
        $finalScore = (float) max(0.00, min(100.00, $finalScore));

        // Find appropriate classification matching the score
        $classification = RiskClassification::where('min_score', '<=', $finalScore)
            ->where('max_score', '>=', $finalScore)
            ->first();

        $classificationId = $classification ? $classification->id : 1; // default fallback ID
        $riskLevel = $classification ? $classification->name : 'Medium';

        // Save risk calculation score
        $riskScore = $this->riskRepo->saveRiskScore($countryId, [
            'classification_id' => $classificationId,
            'weather_score' => $weatherRaw,
            'inflation_score' => $inflationRaw,
            'currency_score' => $currencyRaw,
            'political_score' => $politicalRaw,
            'final_risk_score' => $finalScore,
            'risk_level' => $riskLevel,
            'source_version' => '1.0.0',
        ]);

        // Save components breakdowns (category IDs weather = 1, inflation = 2, political = 3, currency = 4)
        $this->riskRepo->saveRiskComponent($riskScore->id, 1, 'Severe Weather events', $weatherRaw, 0.30, $weatherWeighted);
        $this->riskRepo->saveRiskComponent($riskScore->id, 2, 'Consumer Price Index Inflation', $inflationRaw, 0.20, $inflationWeighted);
        $this->riskRepo->saveRiskComponent($riskScore->id, 3, 'Political Media Sentiment Analysis', $politicalRaw, 0.40, $politicalWeighted);
        $this->riskRepo->saveRiskComponent($riskScore->id, 4, 'Exchange rates fluctuation', $currencyRaw, 0.10, $currencyWeighted);

        return $riskScore;
    }
}
