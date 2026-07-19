<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ExchangeRateService;

class RefreshExchangeRateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected $base;

    public function __construct(string $base = 'USD')
    {
        $this->base = $base;
    }

    public function handle(ExchangeRateService $exchangeRateService): void
    {
        try {
            // Force refresh the cache via service
            $exchangeRateService->getLatest($this->base, true);
            
            \Illuminate\Support\Facades\Cache::put('api_last_sync_exchange_rate', now()->toIso8601String());
            \Illuminate\Support\Facades\Cache::put('api_status_exchange_rate', 'Operational');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Cache::put('api_status_exchange_rate', 'Offline');
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        \Illuminate\Support\Facades\Log::error('RefreshExchangeRateJob failed permanently: ' . $exception->getMessage());
    }
}