<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\RiskTrendService;
use App\Services\CountryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateRiskTrendJob implements ShouldQueue
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
        RiskTrendService $service,
        CountryService $countryService
    ): void {
        if ($this->countryId) {
            $country = $countryService->getCountryById($this->countryId);
            if ($country) {
                Log::info("GenerateRiskTrendJob: Starting single country risk trend analysis for country: {$country->code}");
                $service->analyzeForCountry($country, true);
                
                // Dispatch alert generation for this country
                \App\Jobs\GenerateAlertJob::dispatch($country->id);
            } else {
                Log::warning("GenerateRiskTrendJob: Country not found for ID: {$this->countryId}");
            }
        } else {
            Log::info("GenerateRiskTrendJob: Starting bulk country risk trend analysis...");
            $countries = $countryService->getAllCountries();
            foreach ($countries as $country) {
                // Dispatch individual single country jobs to split work across workers
                self::dispatch($country->id);
            }
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateRiskTrendJob failed permanently: ' . $exception->getMessage());
    }
}
