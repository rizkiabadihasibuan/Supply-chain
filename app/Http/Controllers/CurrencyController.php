<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * POST /countries/{code}/sync-currency
     * Synchronize currency rates for a specific country.
     */
    public function sync(string $code, Request $request): JsonResponse
    {
        try {
            $country = $this->currencyService->syncCountryCurrency($code, true);

            return response()->json([
                'success' => true,
                'message' => "Data kurs negara '{$country->name}' ({$country->currency_code}) berhasil diperbarui dari Exchange Rate API.",
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal menyelaraskan kurs negara '{$code}': ".$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /countries/sync-currency
     * Synchronize currency rates for all countries.
     */
    public function syncAll(Request $request): JsonResponse
    {
        try {
            $results = $this->currencyService->syncAllCurrencies(true);

            $successCount = count($results['success']);
            $failedCount = count($results['failed']);

            return response()->json([
                'success' => true,
                'message' => "Sinkronisasi kurs seluruh negara selesai. Sukses: {$successCount}, Gagal: {$failedCount}.",
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyelaraskan kurs seluruh negara: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
