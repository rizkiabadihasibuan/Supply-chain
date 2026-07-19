<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\WorldBankService;

class RefreshWorldBankJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected $countryCode;

    public function __construct(string $countryCode)
    {
        $this->countryCode = $countryCode;
    }

    public function handle(WorldBankService $worldBankService): void
    {
        try {
            // Force refresh the cache via service
            $worldBankService->getEconomicData($this->countryCode, true);
            
            \Illuminate\Support\Facades\Cache::put('api_last_sync_world_bank', now()->toIso8601String());
            \Illuminate\Support\Facades\Cache::put('api_status_world_bank', 'Operational');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Cache::put('api_status_world_bank', 'Offline');
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        \Illuminate\Support\Facades\Log::error('RefreshWorldBankJob failed permanently: ' . $exception->getMessage());
    }
}