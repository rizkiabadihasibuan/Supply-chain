<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\RestCountriesService;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    protected $restCountriesService;

    /**
     * CountryController constructor.
     *
     * @param RestCountriesService $restCountriesService
     */
    public function __construct(RestCountriesService $restCountriesService)
    {
        $this->restCountriesService = $restCountriesService;
    }

    /**
     * Retrieve country details from API (or Cache).
     *
     * @param string $code
     * @return JsonResponse
     */
    public function detail(string $code): JsonResponse
    {
        $data = $this->restCountriesService->fetchByCode($code);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => "Detail negara dengan kode '{$code}' tidak dapat ditemukan atau API eksternal terganggu."
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Sync local country data with REST Countries API.
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

        $apiData = $this->restCountriesService->fetchByCode($code);

        if (!$apiData) {
            return response()->json([
                'success' => false,
                'message' => "Gagal mengambil data terbaru dari REST Countries API."
            ], 502);
        }

        // Sync local record
        $country->name = $apiData['name'] ?? $country->name;
        $country->region = $apiData['region'] ?? $country->region;
        $country->population = $apiData['population'] ?? $country->population;
        $country->currency_code = $apiData['currency_code'] ?? $country->currency_code;
        $country->currency_name = $apiData['currency_name'] ?? $country->currency_name;
        $country->language = $apiData['language'] ?? $country->language;
        $country->save();

        return response()->json([
            'success' => true,
            'message' => "Data lokal negara '{$country->name}' berhasil disinkronisasikan dengan REST Countries API.",
            'data' => $apiData
        ]);
    }
}
