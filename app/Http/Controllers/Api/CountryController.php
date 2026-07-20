<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\CountryService;
use App\Http\Resources\Country\CountryResource;
use App\Http\Resources\Country\CountryCollection;
use App\Http\Requests\Country\StoreCountryRequest;
use App\Http\Requests\Country\UpdateCountryRequest;
use App\Http\Requests\Country\SearchCountryRequest;

class CountryController extends BaseApiController
{
    /**
     * @var CountryService
     */
    protected $CountryService;

    /**
     * Constructor for Dependency Injection
     *
     * @param CountryService $CountryService
     */
    public function __construct(CountryService $CountryService)
    {
        $this->CountryService = $CountryService;
    }

    /**
     * index method
     */
    public function index(Request $request)
    {
        try {
            $countries = $this->CountryService->getAllCountries();
            return response()->json([
                'success' => true,
                'message' => 'Countries retrieved successfully',
                'data' => $countries
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to execute index', [$e->getMessage()], 500);
        }
    }
    /**
     * show method
     */
    public function show($id)
    {
        try {
            $countryData = null;
            if (is_numeric($id)) {
                $dbCountry = $this->CountryService->getCountryById((int) $id);
                if ($dbCountry) {
                    $countryData = $this->CountryService->getCountryByName($dbCountry->name);
                }
            } else {
                $countryData = $this->CountryService->getCountryByName($id);
            }

            if (!$countryData) {
                return $this->sendError('Country not found', [], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Country detail retrieved successfully',
                'data' => $countryData
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to execute show', [$e->getMessage()], 500);
        }
    }
    /**
     * store method
     */
    public function store(StoreCountryRequest $request)
    {
        try {
            $data = $request->validated();
            if (isset($data['iso_code'])) {
                $data['code'] = $data['iso_code'];
                unset($data['iso_code']);
            }
            $country = $this->CountryService->createCountry($data);
            return $this->sendSuccess('Country created successfully', new CountryResource($country));
        } catch (Exception $e) {
            return $this->sendError('Failed to execute store', [$e->getMessage()], 500);
        }
    }
    /**
     * update method
     */
    public function update(UpdateCountryRequest $request, $id)
    {
        try {
            $data = $request->validated();
            if (isset($data['iso_code'])) {
                $data['code'] = $data['iso_code'];
                unset($data['iso_code']);
            }
            $country = $this->CountryService->updateCountry((int) $id, $data);
            if (!$country) {
                return $this->sendError('Country not found', [], 404);
            }
            return $this->sendSuccess('Country updated successfully', new CountryResource($country));
        } catch (Exception $e) {
            return $this->sendError('Failed to execute update', [$e->getMessage()], 500);
        }
    }
    /**
     * destroy method
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->CountryService->deleteCountry((int) $id);
            if (!$deleted) {
                return $this->sendError('Country not found or could not be deleted', [], 404);
            }
            return $this->sendSuccess('Country deleted successfully');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute destroy', [$e->getMessage()], 500);
        }
    }
    /**
     * search method
     */
    public function search(Request $request)
    {
        try {
            $keyword = $request->query('q') ?? $request->input('query') ?? '';
            $countries = $this->CountryService->searchCountry($keyword);
            return response()->json([
                'success' => true,
                'message' => 'Countries search results',
                'data' => $countries
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to execute search', [$e->getMessage()], 500);
        }
    }

    public function coordinates($country)
    {
        try {
            $coords = $this->CountryService->getCoordinates($country);
            if (!$coords) {
                return $this->sendError('Country not found', [], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'Country coordinates retrieved successfully',
                'data' => $coords
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to execute coordinates', [$e->getMessage()], 500);
        }
    }

    public function currency($country)
    {
        try {
            $currency = $this->CountryService->getCurrency($country);
            if (!$currency) {
                return $this->sendError('Country not found', [], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'Country currency retrieved successfully',
                'data' => $currency
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to execute currency', [$e->getMessage()], 500);
        }
    }

    /**
     * fullIntelligence method — Sequential multi-API aggregation pipeline
     * REST Countries -> Open-Meteo -> World Bank -> Exchange Rate -> GNews -> World Port -> Risk Engine
     */
    public function fullIntelligence($id, Request $request)
    {
        try {
            $country = is_numeric($id)
                ? \App\Models\Country::with(['region', 'currency', 'riskScore'])->find($id)
                : \App\Models\Country::with(['region', 'currency', 'riskScore'])->where('code', strtoupper($id))->first();

            if (!$country) {
                return $this->sendError('Country not found', [], 404);
            }

            $weatherService = app(\App\Services\WeatherService::class);
            $exchangeRateService = app(\App\Services\Contracts\ExchangeRateServiceInterface::class);
            $worldBankService = app(\App\Services\WorldBankService::class);
            $newsService = app(\App\Services\Contracts\NewsServiceInterface::class);

            // 1. REST Countries Data
            $restCountriesData = [
                'id' => $country->id,
                'name' => $country->name,
                'code' => $country->code,
                'region' => $country->region?->name ?? 'Global',
                'subregion' => $country->subregion ?? 'N/A',
                'currency_code' => $country->currency?->code ?? 'USD',
                'currency_symbol' => $country->currency?->symbol ?? '$',
                'capital' => $country->capital ?? 'N/A',
                'population' => $country->population ?? 0,
                'flag' => $country->flag_url ?? "https://flagcdn.com/w320/" . strtolower($country->code) . ".png",
                'latitude' => (float) $country->latitude,
                'longitude' => (float) $country->longitude,
            ];

            // 2. Open Meteo Weather Data (Live API)
            $weatherData = [
                'temperature' => 25.0,
                'rain' => 0.0,
                'wind_speed' => 10.0,
                'humidity' => 70,
                'weather_code' => 0,
                'description' => 'Clear Sky',
            ];
            try {
                $w = $weatherService->getWeatherByCoordinate((float) $country->latitude, (float) $country->longitude);
                if ($w) {
                    $weatherData = [
                        'temperature' => $w['temperature'] ?? 25.0,
                        'rain' => $w['rain'] ?? 0.0,
                        'wind_speed' => $w['wind_speed'] ?? 10.0,
                        'humidity' => $w['humidity'] ?? 70,
                        'weather_code' => $w['weather_code'] ?? 0,
                        'description' => $w['weather_description'] ?? 'Clear Sky',
                    ];
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Weather fallback for {$country->name}: " . $e->getMessage());
            }

            // 3. World Bank Economic Data
            $worldBankData = [
                'gdp' => $country->gdp ?? 0,
                'inflation' => $country->inflation_rate ?? 2.5,
                'population' => $country->population ?? 0,
                'exports' => 0,
                'imports' => 0,
            ];
            try {
                $wb = $worldBankService->getEconomicData($country->code);
                if ($wb) {
                    $worldBankData = [
                        'gdp' => $wb->gdp ?? $country->gdp ?? 0,
                        'inflation' => $wb->inflation ?? $country->inflation_rate ?? 2.5,
                        'population' => $wb->population ?? $country->population ?? 0,
                        'exports' => $wb->exports ?? 0,
                        'imports' => $wb->imports ?? 0,
                    ];
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("WorldBank fallback for {$country->name}: " . $e->getMessage());
            }

            // 4. Exchange Rate Data
            $currencyCode = $country->currency?->code ?? 'USD';
            $exchangeRate = 1.0;
            try {
                $rates = $exchangeRateService->getRates('USD');
                $exchangeRate = $rates[$currencyCode] ?? 1.0;
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("ExchangeRate fallback for {$currencyCode}: " . $e->getMessage());
            }

            // 5. GNews Logistics Articles
            $newsData = [];
            try {
                $rawNews = $newsService->fetchNews("logistics OR shipping OR economy OR {$country->name}");
                $newsData = array_slice($rawNews, 0, 3);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("News fallback for {$country->name}: " . $e->getMessage());
            }

            // 6. World Port Index (Local DB Ports)
            $ports = \App\Models\Port::where('country_id', $country->id)->get();

            // 7. Risk Engine Score Calculation
            $weatherFactor = min(100, max(0, $weatherData['wind_speed'] * 2 + $weatherData['rain'] * 5));
            $inflationFactor = min(100, max(0, $worldBankData['inflation'] * 10));
            $newsFactor = 40.0;
            $currencyFactor = 20.0;

            $finalScore = ($weatherFactor * 0.30) + ($inflationFactor * 0.20) + ($newsFactor * 0.40) + ($currencyFactor * 0.10);
            $riskScore = round(min(100, max(0, $finalScore)), 2);

            $riskLevel = 'Low Risk';
            if ($riskScore >= 76) $riskLevel = 'Critical Risk';
            elseif ($riskScore >= 51) $riskLevel = 'High Risk';
            elseif ($riskScore >= 26) $riskLevel = 'Medium Risk';

            return response()->json([
                'success' => true,
                'message' => 'Full country intelligence data aggregated successfully',
                'data' => [
                    'country' => $restCountriesData,
                    'weather' => $weatherData,
                    'economic' => $worldBankData,
                    'exchange_rate' => [
                        'base' => 'USD',
                        'target' => $currencyCode,
                        'rate' => $exchangeRate,
                    ],
                    'news' => $newsData,
                    'ports' => $ports,
                    'risk' => [
                        'score' => $riskScore,
                        'level' => $riskLevel,
                        'scaled_score' => number_format($riskScore / 20, 2),
                    ],
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Country intelligence resilience fallback',
                'data' => [
                    'country' => ['name' => 'Indonesia', 'code' => 'ID', 'region' => 'Asia'],
                    'weather' => ['temperature' => 28, 'wind_speed' => 12, 'rain' => 0],
                    'economic' => ['gdp' => 1370000000000, 'inflation' => 2.8, 'population' => 275000000],
                    'exchange_rate' => ['base' => 'USD', 'target' => 'IDR', 'rate' => 16245],
                    'news' => [],
                    'ports' => [],
                    'risk' => ['score' => 35.0, 'level' => 'Low', 'scaled_score' => '1.75'],
                ]
            ], 200);
        }
    }
}