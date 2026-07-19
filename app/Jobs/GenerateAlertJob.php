<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\AlertEngineService;
use App\Services\CountryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateAlertJob implements ShouldQueue
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

    protected ?int $countryId;

    public function __construct(?int $countryId = null)
    {
        $this->countryId = $countryId;
    }

    public function handle(
        AlertEngineService $service,
        CountryService $countryService
    ): void {
        if ($this->countryId) {
            $country = $countryService->getCountryById($this->countryId);
            if ($country) {
                Log::info("GenerateAlertJob: Starting single country alert generation for: {$country->code}");
                $service->generateAlertsForCountry($country);
            } else {
                Log::warning("GenerateAlertJob: Country not found for ID: {$this->countryId}");
            }
        } else {
            Log::info("GenerateAlertJob: Starting bulk alert generation for all countries...");
            $countries = $countryService->getAllCountries();
            foreach ($countries as $country) {
                self::dispatch($country->id);
            }
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateAlertJob failed permanently: ' . $exception->getMessage(), [
            'country_id' => $this->countryId,
            'exception' => $exception,
        ]);
    }
}
