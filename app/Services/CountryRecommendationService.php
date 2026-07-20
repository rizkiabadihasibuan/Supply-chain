<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Country;
use Illuminate\Support\Collection;

class CountryRecommendationService
{
    /**
     * Get multi-criteria recommendations for importing goods.
     *
     * @param array $filters
     * @return Collection
     */
    public function getRecommendations(array $filters = []): Collection
    {
        $region = strtolower($filters['region'] ?? 'all');
        $maxRisk = (float) ($filters['max_risk'] ?? 100.0);
        $preferredCurrency = strtoupper($filters['currency'] ?? '');

        $query = Country::with(['region', 'currency', 'riskScore']);

        if ($region !== 'all' && !empty($region)) {
            $query->whereHas('region', function ($q) use ($region) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$region}%"]);
            });
        }

        if (!empty($preferredCurrency)) {
            $query->whereHas('currency', function ($q) use ($preferredCurrency) {
                $q->where('code', $preferredCurrency);
            });
        }

        $countries = $query->get();

        return $countries->map(function ($c) use ($maxRisk) {
            $riskScoreVal = (float) ($c->riskScore?->final_risk_score ?? 20.0);
            $riskLevelVal = strtolower($c->riskScore?->risk_level ?? 'low');

            // Skip if exceeds max risk filter
            if ($riskScoreVal > $maxRisk) {
                return null;
            }

            // Calculate Import Suitability Score (0-100%)
            $suitabilityScore = max(0.0, min(100.0, 100.0 - $riskScoreVal));

            // Recommendation grade & status
            $status = match (true) {
                $suitabilityScore >= 80.0 => 'Sangat Direkomendasikan',
                $suitabilityScore >= 60.0 => 'Layak Impor',
                $suitabilityScore >= 40.0 => 'Perlu Pengawasan Ketat',
                default => 'Tidak Direkomendasikan',
            };

            $badgeClass = match (true) {
                $suitabilityScore >= 80.0 => 'success',
                $suitabilityScore >= 60.0 => 'info',
                $suitabilityScore >= 40.0 => 'warning',
                default => 'danger',
            };

            // Justification points
            $reasons = [];
            if ($riskScoreVal < 30.0) {
                $reasons[] = 'Stabilitas iklim politik dan jalur logistik sangat tinggi';
            } elseif ($riskScoreVal < 60.0) {
                $reasons[] = 'Risiko sedang; fluktuasi pasokan masih dalam batas normal';
            } else {
                $reasons[] = 'Terdapat potensi gangguan geopolitik atau cuaca ekstrem';
            }

            if (in_array(($c->currency?->code ?? ''), ['USD', 'EUR', 'SGD'])) {
                $reasons[] = 'Mata uang terbilang stabil (' . $c->currency->code . ') untuk transaksi dagang';
            }

            return [
                'id' => $c->id,
                'name' => $c->name,
                'code' => $c->code,
                'capital' => $c->capital ?? 'N/A',
                'region' => $c->region?->name ?? 'Global',
                'currency' => $c->currency?->code ?? 'USD',
                'flag_url' => $c->flag_url,
                'risk_score' => round($riskScoreVal, 2),
                'risk_level' => ucfirst($riskLevelVal),
                'suitability_score' => round($suitabilityScore, 1),
                'recommendation_status' => $status,
                'badge_class' => $badgeClass,
                'reasons' => $reasons,
            ];
        })
        ->filter()
        ->sortByDesc('suitability_score')
        ->values();
    }
}
