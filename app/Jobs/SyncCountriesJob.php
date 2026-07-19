<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncCountriesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    public function handle(\App\Services\CountryService $countryService): void
    {
        try {
            Log::info("Starting SyncCountriesJob...");
            $countryService->syncCountries(true);
            Log::info("Finished SyncCountriesJob.");
            
            \Illuminate\Support\Facades\Cache::put('api_last_sync_countries', now()->toIso8601String());
            \Illuminate\Support\Facades\Cache::put('api_status_countries', 'Operational');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Cache::put('api_status_countries', 'Offline');
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("SyncCountriesJob failed permanently: " . $exception->getMessage());
    }
}
