<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Services\WorldBankService;
use App\Services\Contracts\WeatherServiceInterface;
use App\Services\Contracts\ExchangeRateServiceInterface;
use Exception;

class ComparisonController extends BaseApiController
{
    protected $worldBankService;
    protected $weatherService;
    protected $exchangeRateService;

    public function __construct(
        WorldBankService $worldBankService,
        WeatherServiceInterface $weatherService,
        ExchangeRateServiceInterface $exchangeRateService
    ) {
        $this->worldBankService = $worldBankService;
        $this->weatherService = $weatherService;
        $this->exchangeRateService = $exchangeRateService;
    }

    private function getEmojiFlag(string $countryCode): string
    {
        if (strlen($countryCode) !== 2) {
            return '🌐';
        }
        return implode('', array_map(function($char) {
            return mb_chr(ord($char) + 127397);
        }, str_split(strtoupper($countryCode))));
    }

    public function index(Request $request)
    {
        $codeA = $request->query('country_a');
        $codeB = $request->query('country_b');

        if (!$codeA || !$codeB) {
            return $this->sendError('Silakan pilih dua negara untuk dibandingkan.', [], 400);
        }

        try {
            $findCountry = function ($queryStr) {
                return Country::with(['riskScore', 'currency', 'region'])
                    ->where('code', strtoupper($queryStr))
                    ->orWhere('name', 'like', '%' . $queryStr . '%')
                    ->first();
            };

            $countryA = $findCountry($codeA);
            $countryB = $findCountry($codeB);

            if (!$countryA || !$countryB) {
                return $this->sendError('Salah satu atau kedua negara tidak ditemukan.', [], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Perbandingan negara berhasil diproses',
                'data' => [
                    'country_a' => $this->getCountryComparisonData($countryA),
                    'country_b' => $this->getCountryComparisonData($countryB),
                ]
            ]);

        } catch (Exception $e) {
            return $this->sendError('Gagal memproses perbandingan.', [$e->getMessage()], 500);
        }
    }

    private function getCountryComparisonData(Country $country): array
    {
        $code = strtoupper($country->code);
        $capitals = [
            'ID' => 'Jakarta',
            'CN' => 'Beijing',
            'US' => 'Washington D.C.',
            'NL' => 'Amsterdam',
            'SD' => 'Khartoum',
            'SG' => 'Singapura',
            'JP' => 'Tokyo',
            'DE' => 'Berlin',
            'AU' => 'Canberra',
            'BR' => 'Brasilia',
            'ZA' => 'Pretoria',
            'CH' => 'Bern',
            'DK' => 'Kopenhagen',
            'UA' => 'Kyiv',
        ];

        // 1. World Bank indicators
        $gdp = 1.0;
        $inflation = 2.0;
        $exports = 0.0;
        $imports = 0.0;
        $population = ($country->population) ? ($country->population / 1000000) : 100.0;

        try {
            $economic = $this->worldBankService->getEconomicData($code);
            $gdp = $economic->gdp ? ($economic->gdp / 1e12) : 1.0;
            $inflation = $economic->inflation ?? 2.0;
            $exports = $economic->exports ? ($economic->exports / 1e9) : 0.0;
            $imports = $economic->imports ? ($economic->imports / 1e9) : 0.0;
            if ($economic->population) {
                $population = $economic->population / 1000000;
            }
        } catch (Exception $e) {
            // Fallback default values
        }

        // 2. Weather
        $temp = 25.0;
        if ($country->latitude && $country->longitude) {
            try {
                $weather = $this->weatherService->getWeatherData($country->latitude, $country->longitude);
                $temp = $weather['current']['temperature_2m'] ?? 25.0;
            } catch (Exception $e) {
            }
        }
        $weatherLabel = ($temp > 28) ? 'Panas ☀️' : (($temp > 18) ? 'Cerah Berawan ⛅' : 'Sejuk 🌥');

        // 3. Currency Rate
        $currencyCode = $country->currency ? $country->currency->code : 'USD';
        $currencyRateText = 'Base Currency';
        if ($currencyCode !== 'USD') {
            try {
                $rates = $this->exchangeRateService->getRates('USD');
                if (isset($rates[$currencyCode])) {
                    $currencyRateText = $currencyCode . ' ' . number_format($rates[$currencyCode], 2) . ' / USD';
                }
            } catch (Exception $e) {
            }
        }

        // 4. Risk Score
        $riskScore = $country->riskScore ? $country->riskScore->final_risk_score : 25.0;
        $riskLevel = $country->riskScore ? $country->riskScore->risk_level : 'Low';

        return [
            'code' => $code,
            'flag' => $this->getEmojiFlag($code),
            'flag_url' => $country->flag_url,
            'name' => $country->name,
            'region' => $country->region?->name ?? $country->subregion ?? 'Global',
            'capital' => $capitals[$code] ?? $country->timezone ?? 'Capital City',
            'currency' => $currencyCode,
            'currencyRate' => $currencyRateText,
            'gdp' => round($gdp, 2),
            'gdpLabel' => '$' . number_format($gdp, 2) . 'T',
            'inflation' => round($inflation, 2),
            'inflationLabel' => number_format($inflation, 2) . '%',
            'population' => round($population, 2),
            'populationLabel' => number_format($population, 2) . ' Juta',
            'weather' => $temp . '°C',
            'weatherLabel' => $weatherLabel,
            'riskScore' => round($riskScore, 1),
            'riskLevel' => $riskLevel,
            'export' => '$' . number_format($exports, 1) . 'B',
            'import' => '$' . number_format($imports, 1) . 'B',
            'growth' => '+2.5%',
        ];
    }
}
