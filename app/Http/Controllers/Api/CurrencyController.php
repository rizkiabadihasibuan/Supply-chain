<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\Contracts\CurrencyServiceInterface;
use App\Http\Resources\Currency\CurrencyResource;
use App\Http\Resources\Currency\ExchangeRateResource;
use App\Http\Requests\Currency\CurrencyFilterRequest;
use App\Http\Requests\Currency\CurrencyRefreshRequest;

class CurrencyController extends BaseApiController
{
    /**
     * @var CurrencyServiceInterface
     */
    protected $CurrencyService;

    /**
     * Constructor for Dependency Injection
     *
     * @param CurrencyServiceInterface $CurrencyService
     */
    public function __construct(CurrencyServiceInterface $CurrencyService)
    {
        $this->CurrencyService = $CurrencyService;
    }

    /**
     * index method
     */
    public function index(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->CurrencyService->index(...);
            // return new ExchangeRateResource($result);
            return $this->sendSuccess('Method index executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute index', [$e->getMessage()], 500);
        }
    }
    /**
     * filter method
     */
    public function filter(CurrencyFilterRequest $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->CurrencyService->filter(...);
            // return new ExchangeRateResource($result);
            return $this->sendSuccess('Method filter executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute filter', [$e->getMessage()], 500);
        }
    }
    /**
     * refresh method
     */
    public function refresh(CurrencyRefreshRequest $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->CurrencyService->refresh(...);
            // return new CurrencyResource($result);
            return $this->sendSuccess('Method refresh executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute refresh', [$e->getMessage()], 500);
        }
    }

}