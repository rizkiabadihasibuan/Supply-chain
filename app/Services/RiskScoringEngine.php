<?php

namespace App\Services;

use App\Models\Country;
use App\Models\RiskScore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RiskScoringEngine
{
    protected $gnewsService;

    protected $sentimentAnalyzer;

    /**
     * RiskScoringEngine constructor.
     */
    public function __construct(GNewsService $gnewsService, SentimentAnalyzer $sentimentAnalyzer)
    {
        $this->gnewsService = $gnewsService;
        $this->sentimentAnalyzer = $sentimentAnalyzer;
    }

    /**
     * Calculate and record the risk score for a country.
     */
    public function calculateRisk(Country $country): RiskScore
    {
        // 1. Weather Risk (30% weight)
        // Derived from current_weather_storm_risk stored in the country table
        $weatherRisk = (float) ($country->current_weather_storm_risk ?? 0.0);

        // 2. Inflation Risk (20% weight)
        // Normal/safe inflation target is around 2.0%.
        // Deviations or extremely high inflation increases the risk.
        $inflation = $country->inflation !== null ? (float) $country->inflation : null;
        $inflationRisk = 20.0; // Default if not available

        if ($inflation !== null) {
            if ($inflation <= 0) {
                // Deflation is also risky
                $inflationRisk = min(100.0, abs($inflation) * 20.0);
            } else {
                if ($inflation <= 3.0) {
                    $inflationRisk = ($inflation / 3.0) * 25.0; // Low inflation risk
                } else {
                    $inflationRisk = min(100.0, 25.0 + ($inflation - 3.0) * 12.0); // Escalating risk
                }
            }
        }

        // 3. Currency Risk (10% weight)
        // Read the volatility parameter from the cache (seeded by CurrencySeeder or cached rates)
        // Volatility is multiplied to get a score between 0 and 100
        $currencyCode = $country->currency_code ?? 'USD';
        $cacheKey = "currency_rate_USD_{$currencyCode}";
        $currencyData = Cache::get($cacheKey);

        $volatility = 1.50; // Default volatility fallback
        if ($currencyData && isset($currencyData['volatility'])) {
            $volatility = (float) $currencyData['volatility'];
        }

        $currencyRisk = min(100.0, max(0.0, $volatility * 35.0));

        // 4. Political News Risk / News Sentiment Risk (40% weight)
        // Fetch news for the country and run sentiment analysis
        $news = $this->gnewsService->fetchNews($country->code, $country->name);
        $sentimentResult = $this->sentimentAnalyzer->analyzeArticles($news);

        // News Sentiment Risk is based on the Negative Sentiment percentage
        $politicalRisk = (float) ($sentimentResult['negative_percent'] ?? 0.0);

        // Calculate Weighted Total Risk Score
        $totalRiskScore = (0.30 * $weatherRisk) +
                          (0.20 * $inflationRisk) +
                          (0.10 * $currencyRisk) +
                          (0.40 * $politicalRisk);

        $totalRiskScore = round(min(100.0, max(0.0, $totalRiskScore)), 2);

        // Determine Risk Level
        if ($totalRiskScore < 35.0) {
            $riskLevel = 'Low';
        } elseif ($totalRiskScore <= 70.0) {
            $riskLevel = 'Medium';
        } else {
            $riskLevel = 'High';
        }

        // Create a new risk score record in the database
        $riskScore = RiskScore::create([
            'country_id' => $country->id,
            'weather_risk_score' => $weatherRisk,
            'inflation_risk_score' => $inflationRisk,
            'political_risk_score' => $politicalRisk,
            'currency_risk_score' => $currencyRisk,
            'total_risk_score' => $totalRiskScore,
            'risk_level' => $riskLevel,
            'calculated_at' => now(),
        ]);

        Log::info("Calculated risk for {$country->name}: Score={$totalRiskScore}%, Level={$riskLevel}");

        return $riskScore;
    }
}
