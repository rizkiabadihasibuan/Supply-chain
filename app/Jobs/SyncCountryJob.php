<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncCountryJob implements ShouldQueue
{
    use Queueable;

    public $countryCode;
    public $forceRefresh;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array
     */
    public $backoff = [10, 30, 60];

    /**
     * Create a new job instance.
     */
    public function __construct(string $countryCode, bool $forceRefresh = false)
    {
        $this->countryCode = $countryCode;
        $this->forceRefresh = $forceRefresh;
    }

    /**
     * Execute the job.
     */
    public function handle(\App\Services\CountryService $countryService): void
    {
        $countryService->syncCountry($this->countryCode, $this->forceRefresh);
    }
}
