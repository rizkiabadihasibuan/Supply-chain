<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\Contracts\CurrencyServiceInterface;
use App\Services\Contracts\ExchangeRateServiceInterface;
use App\Models\Currency;

class CurrencyController extends BaseApiController
{
    /**
     * @var CurrencyServiceInterface
     */
    protected $CurrencyService;

    /**
     * @var ExchangeRateServiceInterface
     */
    protected $exchangeRateService;

    /**
     * Constructor for Dependency Injection
     */
    public function __construct(
        CurrencyServiceInterface $CurrencyService,
        ExchangeRateServiceInterface $exchangeRateService
    ) {
        $this->CurrencyService = $CurrencyService;
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * index method — Get real-time exchange rates
     * GET /api/v1/currencies or GET /api/v1/currency
     */
    public function index(Request $request)
    {
        try {
            $base = strtoupper($request->query('base', 'USD'));
            $forceRefresh = $request->boolean('refresh', false);

            $rates = $this->exchangeRateService->getRates($base, $forceRefresh);

            // Get currency metadata from database
            $dbCurrencies = Currency::all()->keyBy('code');

            $formattedRates = [];
            foreach ($rates as $code => $rate) {
                $meta = $dbCurrencies->get($code);
                $formattedRates[$code] = [
                    'code' => $code,
                    'name' => $meta?->name ?? $code,
                    'symbol' => $meta?->symbol ?? $code,
                    'rate' => (float) $rate,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Exchange rates retrieved successfully',
                'data' => [
                    'base_currency' => $base,
                    'total' => count($formattedRates),
                    'rates' => $formattedRates,
                    'updated_at' => now()->toIso8601String(),
                ],
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve exchange rates', [$e->getMessage()], 500);
        }
    }

    /**
     * filter method — Filter exchange rates by symbols/target currencies
     * GET /api/v1/exchange-rate?base=USD&symbols=IDR,EUR,GBP,CNY,SGD
     */
    public function filter(Request $request)
    {
        try {
            $base = strtoupper($request->query('base', 'USD'));
            $symbolsInput = $request->query('symbols', $request->query('target', ''));
            
            $rates = $this->exchangeRateService->getRates($base);

            if (!empty($symbolsInput)) {
                $symbols = array_map('trim', array_map('strtoupper', explode(',', $symbolsInput)));
                $rates = array_intersect_key($rates, array_flip($symbols));
            }

            return response()->json([
                'success' => true,
                'message' => 'Filtered exchange rates retrieved successfully',
                'data' => [
                    'base_currency' => $base,
                    'rates' => $rates,
                ],
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to filter exchange rates', [$e->getMessage()], 500);
        }
    }

    /**
     * refresh method — Force refresh exchange rates from API
     * POST /api/v1/exchange-rate/refresh
     */
    public function refresh(Request $request)
    {
        try {
            $base = strtoupper($request->input('base', 'USD'));
            $rates = $this->exchangeRateService->getRates($base, true);

            return response()->json([
                'success' => true,
                'message' => 'Exchange rates refreshed successfully from live API',
                'data' => [
                    'base_currency' => $base,
                    'total_currencies' => count($rates),
                    'refreshed_at' => now()->toIso8601String(),
                ],
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to refresh exchange rates', [$e->getMessage()], 500);
        }
    }
}