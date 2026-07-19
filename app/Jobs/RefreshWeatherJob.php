<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\WeatherService;

class RefreshWeatherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected $latitude;
    protected $longitude;
    protected $timezone;

    public function __construct(float $latitude, float $longitude, string $timezone = 'UTC')
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->timezone = $timezone;
    }

    public function handle(WeatherService $weatherService): void
    {
        try {
            // Force refresh the cache via service
            $weatherService->getWeather($this->latitude, $this->longitude, $this->timezone, true);
            
            \Illuminate\Support\Facades\Cache::put('api_last_sync_weather', now()->toIso8601String());
            \Illuminate\Support\Facades\Cache::put('api_status_weather', 'Operational');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Cache::put('api_status_weather', 'Offline');
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        \Illuminate\Support\Facades\Log::error('RefreshWeatherJob failed permanently: ' . $exception->getMessage());
    }
}