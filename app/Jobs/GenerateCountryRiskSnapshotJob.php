<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\RiskAggregationService;
use App\Services\CountryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateCountryRiskSnapshotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected ?int $countryId;

    public function __construct(?int $countryId = null)
    {
        $this->countryId = $countryId;
    }

    public function handle(
        RiskAggregationService $service,
        CountryService $countryService
    ): void {
        if ($this->countryId) {
            $country = $countryService->getCountryById($this->countryId);
            if ($country) {
                Log::info("Starting single country risk snapshot aggregation for country: {$country->code}");
                $service->aggregateForCountry($country, true);
            } else {
                Log::warning("Country not found for aggregation with ID: {$this->countryId}");
            }
        } else {
            Log::info("Starting bulk country risk snapshot aggregation...");
            $countries = $countryService->getAllCountries();
            foreach ($countries as $country) {
                // Dispatch a single country job to segment the workload across queue workers
                self::dispatch($country->id);
            }
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateCountryRiskSnapshotJob failed permanently: ' . $exception->getMessage());
    }
}
