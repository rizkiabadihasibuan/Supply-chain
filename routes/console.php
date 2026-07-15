<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily economic data synchronization from World Bank API
Schedule::command('economic:sync --force')->dailyAt('02:00');

// Schedule hourly weather data synchronization from Open Meteo API
Schedule::command('weather:sync --force')->hourly();

// Schedule daily currency data synchronization from Exchange Rate API
Schedule::command('currency:sync --force')->daily();
