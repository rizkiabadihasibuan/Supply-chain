<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class SyncWeatherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:sync 
                            {country? : Kode ISO negara yang ingin disinkronkan} 
                            {--force : Paksa refresh cache dan ambil dari API}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menyinkronkan data cuaca negara di database lokal dengan Open Meteo API';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\WeatherService $weatherService)
    {
        $countryCode = $this->argument('country');
        $force = $this->option('force');

        if ($countryCode) {
            $countryCode = strtoupper(trim($countryCode));
            $this->info("Memulai sinkronisasi data cuaca untuk negara: {$countryCode}...");

            try {
                $country = $weatherService->syncWeather($countryCode, $force);
                $this->info("Sukses! Data cuaca negara '{$country->name}' ({$country->code}) berhasil diperbarui.");
                return self::SUCCESS;
            } catch (\Exception $e) {
                $this->error("Gagal menyinkronkan data cuaca negara '{$countryCode}': " . $e->getMessage());
                return self::FAILURE;
            }
        }

        // Sync all countries
        $countries = \App\Models\Country::all();
        if ($countries->isEmpty()) {
            $this->warn("Tidak ada data negara di database lokal untuk disinkronkan.");
            return self::SUCCESS;
        }

        $this->info("Menyinkronkan data cuaca " . $countries->count() . " negara dari Open Meteo API...");

        $bar = $this->output->createProgressBar($countries->count());
        $bar->start();

        $successCount = 0;
        $failedCount = 0;

        foreach ($countries as $country) {
            if ($country->latitude === null || $country->longitude === null) {
                $failedCount++;
                $this->error("\nNegara {$country->code} tidak memiliki koordinat latitude/longitude.");
                $bar->advance();
                continue;
            }

            try {
                $weatherService->syncWeather($country->code, $force);
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("\nGagal menyinkronkan data cuaca {$country->code}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->line("");
        $this->info("Sinkronisasi data cuaca massal selesai!");
        $this->info("Sukses: {$successCount}, Gagal: {$failedCount}");

        return $failedCount === 0 ? self::SUCCESS : self::FAILURE;
    }
}
