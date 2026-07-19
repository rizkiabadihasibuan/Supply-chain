<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\RiskClassificationService;
use App\Services\CountryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ClassifyRiskJob implements ShouldQueue
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
        RiskClassificationService $service,
        CountryService $countryService
    ): void {
        if ($this->countryId) {
            $country = $countryService->getCountryById($this->countryId);
            if ($country) {
                Log::info("Starting single country risk classification for country: {$country->code}");
                $service->classifyForCountry($country, true);
            } else {
                Log::warning("Country not found for classification with ID: {$this->countryId}");
            }
        } else {
            Log::info("Starting bulk country risk classification...");
            $countries = $countryService->getAllCountries();
            foreach ($countries as $country) {
                // Dispatch a single country job
                self::dispatch($country->id);
            }
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ClassifyRiskJob failed permanently: ' . $exception->getMessage());
    }
}
