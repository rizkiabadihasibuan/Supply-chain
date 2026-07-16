<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\CountryService;
use App\Services\CurrencyService;
use App\Services\ExchangeRateService;
use App\Services\OpenMeteoService;
use App\Services\RestCountriesService;
use App\Services\RiskScoringEngine;
use App\Services\WeatherService;
use App\Services\WorldBankService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{
    protected $restCountriesService;

    protected $worldBankService;

    protected $openMeteoService;

    protected $exchangeRateService;

    protected $riskScoringEngine;

    protected $countryService;

    /**
     * CountryController constructor.
     */
    public function __construct(
        RestCountriesService $restCountriesService,
        WorldBankService $worldBankService,
        OpenMeteoService $openMeteoService,
        ExchangeRateService $exchangeRateService,
        RiskScoringEngine $riskScoringEngine,
        CountryService $countryService
    ) {
        $this->restCountriesService = $restCountriesService;
        $this->worldBankService = $worldBankService;
        $this->openMeteoService = $openMeteoService;
        $this->exchangeRateService = $exchangeRateService;
        $this->riskScoringEngine = $riskScoringEngine;
        $this->countryService = $countryService;
    }

    /**
     * Retrieve country details from APIs (or Cache).
     */
    public function detail(string $code): JsonResponse
    {
        $code = strtoupper(trim($code));
        $restData = $this->restCountriesService->fetchByCode($code);
        $bankData = $this->worldBankService->fetchAllMetrics($code);

        if (! $restData && empty($bankData)) {
            return response()->json([
                'success' => false,
                'message' => "Detail negara dengan kode '{$code}' tidak dapat ditemukan atau API eksternal terganggu.",
            ], 404);
        }

        $weatherData = null;
        if ($restData && isset($restData['latitude']) && isset($restData['longitude'])) {
            $weatherData = $this->openMeteoService->fetchWeather($code, (float) $restData['latitude'], (float) $restData['longitude']);
        }

        $currencyRate = null;
        if ($restData && isset($restData['currency_code'])) {
            $currencyRate = $this->exchangeRateService->getRateAgainstUsd($restData['currency_code']);
        }

        // Fetch latest risk score from local database
        $country = Country::where('code', $code)->first();
        $latestRisk = $country ? $country->riskScores()->latest()->first() : null;

        $mergedData = array_merge(
            $restData ?? [],
            $bankData,
            ['weather' => $weatherData],
            ['exchange_rate' => $currencyRate],
            ['latest_risk' => $latestRisk]
        );

        return response()->json([
            'success' => true,
            'data' => $mergedData,
        ]);
    }

    /**
     * Sync local country data with REST Countries & World Bank & Open-Meteo APIs.
     */
    public function sync(string $code): JsonResponse
    {
        $code = strtoupper(trim($code));
        $restSuccess = false;
        $bankSuccess = false;

        $country = Country::where('code', $code)->first();

        // 1. Try to sync REST Countries data via our new CountryService
        try {
            $country = $this->countryService->syncCountry($code, true);
            $restSuccess = true;
        } catch (\Exception $e) {
            Log::warning("Sinkronisasi REST Countries gagal untuk '{$code}': ".$e->getMessage());
        }

        // 2. Fetch from World Bank
        if ($this->worldBankService) {
            try {
                $bankData = $this->worldBankService->fetchAllMetrics($code);
                if (! empty($bankData)) {
                    if (! $country) {
                        $country = Country::where('code', $code)->first();
                    }
                    if ($country) {
                        $country->gdp = $bankData['gdp'] ?? $country->gdp;
                        $country->inflation = $bankData['inflation'] ?? $country->inflation;
                        $country->population = $bankData['population'] ?? $country->population;
                        $country->export_value = $bankData['export'] ?? $country->export_value;
                        $country->import_value = $bankData['import'] ?? $country->import_value;
                        $country->save();
                        $bankSuccess = true;
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Sinkronisasi World Bank gagal untuk '{$code}': ".$e->getMessage());
            }
        }

        if (! $country) {
            return response()->json([
                'success' => false,
                'message' => "Negara dengan kode '{$code}' tidak terdaftar di database lokal kami.",
            ], 404);
        }

        if (! $restSuccess && ! $bankSuccess) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data terbaru dari REST Countries API dan World Bank API.',
            ], 502);
        }

        // 3. Sync weather if coordinates are available
        $weatherService = app(WeatherService::class);
        if ($weatherService && $country->latitude !== null && $country->longitude !== null) {
            try {
                $weatherService->syncWeather($code, true);
            } catch (\Exception $e) {
                Log::warning("Sinkronisasi WeatherService gagal untuk '{$code}': ".$e->getMessage());
            }
        }

        // 4. Sync currency if currency_code is available
        $currencyService = app(CurrencyService::class);
        if ($currencyService && ! empty($country->currency_code)) {
            try {
                $currencyService->syncCountryCurrency($code, true);
            } catch (\Exception $e) {
                Log::warning("Sinkronisasi CurrencyService gagal untuk '{$code}': ".$e->getMessage());
            }
        }

        // 5. Calculate and save Risk Score via RiskScoringEngine
        if ($this->riskScoringEngine) {
            try {
                $this->riskScoringEngine->calculateRisk($country);
            } catch (\Exception $e) {
                Log::warning("Penghitungan skor risiko gagal untuk '{$code}': ".$e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Data lokal negara '{$country->name}' berhasil disinkronisasikan dengan REST Countries, World Bank, dan Open-Meteo API.",
            'data' => $country->load('riskScores'),
        ]);
    }

    /**
     * Sync all local countries with REST Countries API.
     */
    public function syncAll(): JsonResponse
    {
        try {
            $results = $this->countryService->syncAllCountries(true);

            // Re-calculate risk score for successfully synced countries
            $syncedCountries = Country::whereIn('code', $results['success'])->get();
            foreach ($syncedCountries as $country) {
                if ($this->riskScoringEngine) {
                    $this->riskScoringEngine->calculateRisk($country);
                }
            }

            $successCount = count($results['success']);
            $failedCount = count($results['failed']);

            return response()->json([
                'success' => true,
                'message' => "Sinkronisasi seluruh negara selesai. Sukses: {$successCount}, Gagal: {$failedCount}.",
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan sinkronisasi massal: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync local country economic data with World Bank API.
     */
    public function syncEconomic(string $code): JsonResponse
    {
        $code = strtoupper(trim($code));

        try {
            $country = $this->worldBankService->syncCountryEconomicData($code, true);

            // Re-calculate Risk Score using RiskScoringEngine
            if ($this->riskScoringEngine) {
                $this->riskScoringEngine->calculateRisk($country);
            }

            return response()->json([
                'success' => true,
                'message' => "Data ekonomi negara '{$country->name}' berhasil disinkronisasikan dengan World Bank API.",
                'data' => $country->load('riskScores'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan sinkronisasi data ekonomi: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync all countries' economic data with World Bank API.
     */
    public function syncAllEconomic(): JsonResponse
    {
        try {
            $results = $this->worldBankService->syncAllEconomicData(true);

            // Re-calculate risk score for successfully synced countries
            $syncedCountries = Country::whereIn('code', $results['success'])->get();
            foreach ($syncedCountries as $country) {
                if ($this->riskScoringEngine) {
                    $this->riskScoringEngine->calculateRisk($country);
                }
            }

            $successCount = count($results['success']);
            $failedCount = count($results['failed']);

            return response()->json([
                'success' => true,
                'message' => "Sinkronisasi data ekonomi seluruh negara selesai. Sukses: {$successCount}, Gagal: {$failedCount}.",
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan sinkronisasi data ekonomi massal: '.$e->getMessage(),
            ], 500);
        }
    }
}
