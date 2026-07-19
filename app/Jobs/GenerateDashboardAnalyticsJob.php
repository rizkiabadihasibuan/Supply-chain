<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\DashboardAnalyticsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateDashboardAnalyticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = [10, 30, 60];

    public function handle(DashboardAnalyticsService $service): void
    {
        Log::info("GenerateDashboardAnalyticsJob: Starting dashboard analytics cache pre-warming/refresh...");
        
        $startTime = microtime(true);
        $service->refresh();
        $duration = (microtime(true) - $startTime);

        Log::info("GenerateDashboardAnalyticsJob: Dashboard analytics cache pre-warming finished.", [
            'duration_seconds' => round($duration, 2)
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateDashboardAnalyticsJob failed permanently: ' . $exception->getMessage(), [
            'exception' => $exception
        ]);
    }
}
