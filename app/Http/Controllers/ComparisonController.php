<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    /**
     * Display the comparison engine page.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $countries = Country::orderBy('name')->get();

        $country1Code = strtoupper(trim($request->query('country1', '')));
        $country2Code = strtoupper(trim($request->query('country2', '')));

        $country1 = null;
        $country2 = null;
        $risk1 = null;
        $risk2 = null;

        if (!empty($country1Code)) {
            $country1 = Country::where('code', $country1Code)->first();
            if ($country1) {
                try {
                    if ($country1->latitude === null || $country1->longitude === null || empty($country1->currency_code)) {
                        app(\App\Services\CountryService::class)->syncCountry($country1->code, false);
                        $country1->refresh();
                    }
                    app(\App\Services\WeatherService::class)->syncWeather($country1->code, false);
                    app(\App\Services\CurrencyService::class)->syncCountryCurrency($country1->code, false);
                    app(\App\Services\WorldBankService::class)->syncCountryEconomicData($country1->code, false);
                    $country1->refresh();
                    app(\App\Services\RiskScoringEngine::class)->calculateRisk($country1);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("Auto-sync failed for compared country 1: " . $e->getMessage());
                }
                $risk1 = $country1->riskScores()->latest()->first();
            }
        }

        if (!empty($country2Code)) {
            $country2 = Country::where('code', $country2Code)->first();
            if ($country2) {
                try {
                    if ($country2->latitude === null || $country2->longitude === null || empty($country2->currency_code)) {
                        app(\App\Services\CountryService::class)->syncCountry($country2->code, false);
                        $country2->refresh();
                    }
                    app(\App\Services\WeatherService::class)->syncWeather($country2->code, false);
                    app(\App\Services\CurrencyService::class)->syncCountryCurrency($country2->code, false);
                    app(\App\Services\WorldBankService::class)->syncCountryEconomicData($country2->code, false);
                    $country2->refresh();
                    app(\App\Services\RiskScoringEngine::class)->calculateRisk($country2);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("Auto-sync failed for compared country 2: " . $e->getMessage());
                }
                $risk2 = $country2->riskScores()->latest()->first();
            }
        }

        return view('compare', compact('countries', 'country1', 'country2', 'risk1', 'risk2', 'country1Code', 'country2Code'));
    }
}
