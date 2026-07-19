<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\PortService;

class SyncPortJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected $country;

    public function __construct(string $country = '')
    {
        $this->country = $country;
    }

    public function handle(PortService $portService): void
    {
        try {
            // Force refresh the cache via service and trigger DB sync
            $portService->searchPorts('', $this->country, true);
            
            \Illuminate\Support\Facades\Cache::put('api_last_sync_port', now()->toIso8601String());
            \Illuminate\Support\Facades\Cache::put('api_status_port', 'Operational');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Cache::put('api_status_port', 'Offline');
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        \Illuminate\Support\Facades\Log::error('SyncPortJob failed permanently: ' . $exception->getMessage());
    }
}