<?php

use Illuminate\Support\Facades\Schedule;
use App\Jobs\RefreshWeatherJob;
use App\Jobs\RefreshExchangeRateJob;
use App\Jobs\RefreshWorldBankJob;
use App\Jobs\SyncCountriesJob;
use App\Jobs\SyncNewsJob;
use App\Jobs\SyncPortJob;

/*
|--------------------------------------------------------------------------
| Console Routes & Scheduler
|--------------------------------------------------------------------------
*/

// Weather: 30 menit
Schedule::job(new RefreshWeatherJob(-6.2088, 106.8456, 'Asia/Jakarta'))->everyThirtyMinutes()->withoutOverlapping();

// Exchange Rate: 1 jam
Schedule::job(new RefreshExchangeRateJob('USD'))->hourly()->withoutOverlapping();

// World Bank: 24 jam
Schedule::job(new RefreshWorldBankJob('ID'))->daily()->withoutOverlapping();

// REST Countries: 7 hari
Schedule::job(new SyncCountriesJob())->weekly()->withoutOverlapping();

// GNews: 30 menit
Schedule::job(new SyncNewsJob('us'))->everyThirtyMinutes()->withoutOverlapping();

// World Port: 7 hari
Schedule::job(new SyncPortJob(''))->weekly()->withoutOverlapping();

// Generate Country Risk Snapshot: 6 jam
Schedule::job(new \App\Jobs\GenerateCountryRiskSnapshotJob())->everySixHours()->withoutOverlapping();

// Calculate Country Risk Score: 6 jam
Schedule::job(new \App\Jobs\CalculateRiskScoreJob())->everySixHours()->withoutOverlapping();

// Classify Country Risk Level: 6 jam
Schedule::job(new \App\Jobs\ClassifyRiskJob())->everySixHours()->withoutOverlapping();

// Generate Risk Rankings: 6 jam, diikuti dengan Risk Trends Analysis setelah selesai
Schedule::job(new \App\Jobs\GenerateRiskRankingJob())
    ->everySixHours()
    ->withoutOverlapping()
    ->after(function () {
        dispatch(new \App\Jobs\GenerateRiskTrendJob());
    });

// Generate Intelligent Alerts: 6 jam, diikuti dengan Dashboard Analytics pre-warming setelah selesai
Schedule::job(new \App\Jobs\GenerateAlertJob())
    ->everySixHours()
    ->withoutOverlapping()
    ->after(function () {
        dispatch(new \App\Jobs\GenerateDashboardAnalyticsJob());
    });