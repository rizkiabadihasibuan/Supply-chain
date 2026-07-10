<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\RestCountriesService;
use App\Services\WorldBankService;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    protected $restCountriesService;
    protected $worldBankService;

    /**
     * CountryController constructor.
     *
     * @param RestCountriesService $restCountriesService
     * @param WorldBankService $worldBankService
     */
    public function __construct(RestCountriesService $restCountriesService, WorldBankService $worldBankService)
    {
        $this->restCountriesService = $restCountriesService;
        $this->worldBankService = $worldBankService;
    }

    /**
     * Retrieve country details from APIs (or Cache).
     *
     * @param string $code
     * @return JsonResponse
     */
    public function detail(string $code): JsonResponse
    {
        $code = strtoupper(trim($code));
        $restData = $this->restCountriesService->fetchByCode($code);
        $bankData = $this->worldBankService->fetchAllMetrics($code);

        if (!$restData && empty($bankData)) {
            return response()->json([
                'success' => false,
                'message' => "Detail negara dengan kode '{$code}' tidak dapat ditemukan atau API eksternal terganggu."
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => array_merge($restData ?? [], $bankData)
        ]);
    }

    /**
     * Sync local country data with REST Countries & World Bank APIs.
     *
     * @param string $code
     * @return JsonResponse
     */
    public function sync(string $code): JsonResponse
    {
        $code = strtoupper(trim($code));
        $country = Country::where('code', $code)->first();

        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => "Negara dengan kode '{$code}' tidak terdaftar di database lokal kami."
            ], 404);
        }

        // Fetch from REST Countries
        $restData = $this->restCountriesService->fetchByCode($code);

        // Fetch from World Bank
        $bankData = $this->worldBankService->fetchAllMetrics($code);

        if (!$restData && empty($bankData)) {
            return response()->json([
                'success' => false,
                'message' => "Gagal mengambil data terbaru dari REST Countries API dan World Bank API."
            ], 502);
        }

        // Sync local record with REST Countries info
        if ($restData) {
            $country->name = $restData['name'] ?? $country->name;
            $country->region = $restData['region'] ?? $country->region;
            $country->language = $restData['language'] ?? $country->language;
            $country->currency_code = $restData['currency_code'] ?? $country->currency_code;
            $country->currency_name = $restData['currency_name'] ?? $country->currency_name;
        }

        // Sync local record with World Bank economic indicators
        if (!empty($bankData)) {
            $country->gdp = $bankData['gdp'] ?? $country->gdp;
            $country->inflation = $bankData['inflation'] ?? $country->inflation;
            $country->population = $bankData['population'] ?? $country->population;
            $country->export_value = $bankData['export'] ?? $country->export_value;
            $country->import_value = $bankData['import'] ?? $country->import_value;
        }

        $country->save();

        return response()->json([
            'success' => true,
            'message' => "Data lokal negara '{$country->name}' berhasil disinkronisasikan dengan REST Countries & World Bank API.",
            'data' => $country
        ]);
    }
}
