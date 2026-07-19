<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\NewsService;

class SyncNewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected $country;

    public function __construct(string $country = 'us')
    {
        $this->country = $country;
    }

    public function handle(NewsService $newsService): void
    {
        try {
            // Force refresh the cache via service and trigger DB sync
            $newsService->getRiskIntelligenceNews($this->country, true);
            
            \Illuminate\Support\Facades\Cache::put('api_last_sync_news', now()->toIso8601String());
            \Illuminate\Support\Facades\Cache::put('api_status_news', 'Operational');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Cache::put('api_status_news', 'Offline');
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        \Illuminate\Support\Facades\Log::error('SyncNewsJob failed permanently: ' . $exception->getMessage());
    }
}