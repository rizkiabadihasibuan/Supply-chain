<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class SyncCountriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:sync 
                            {country? : Kode ISO negara yang ingin disinkronkan} 
                            {--force : Paksa refresh cache dan ambil dari API} 
                            {--queue : Jalankan sinkronisasi melalui antrean (queue)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menyinkronkan data negara di database lokal dengan REST Countries API';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\CountryService $countryService)
    {
        $countryCode = $this->argument('country');
        $force = $this->option('force');
        $queue = $this->option('queue');

        if ($countryCode) {
            $countryCode = strtoupper(trim($countryCode));
            $this->info("Memulai sinkronisasi untuk negara: {$countryCode}...");

            if ($queue) {
                \App\Jobs\SyncCountryJob::dispatch($countryCode, $force);
                $this->info("Job sinkronisasi untuk '{$countryCode}' telah dikirim ke antrean.");
                return self::SUCCESS;
            }

            try {
                $country = $countryService->syncCountry($countryCode, $force);
                $this->info("Sukses! Data negara '{$country->name}' ({$country->code}) berhasil diperbarui.");
                return self::SUCCESS;
            } catch (\Exception $e) {
                $this->error("Gagal menyinkronkan negara '{$countryCode}': " . $e->getMessage());
                return self::FAILURE;
            }
        }

        // Sync all countries
        $countries = \App\Models\Country::all();
        if ($countries->isEmpty()) {
            $this->warn("Tidak ada data negara di database lokal untuk disinkronkan.");
            return self::SUCCESS;
        }

        $this->info("Menyinkronkan " . $countries->count() . " negara dari REST Countries API...");

        if ($queue) {
            foreach ($countries as $country) {
                \App\Jobs\SyncCountryJob::dispatch($country->code, $force);
            }
            $this->info("Seluruh job sinkronisasi negara telah dikirim ke antrean.");
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($countries->count());
        $bar->start();

        $successCount = 0;
        $failedCount = 0;

        foreach ($countries as $country) {
            try {
                $countryService->syncCountry($country->code, $force);
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("\nGagal menyinkronkan {$country->code}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->line("");
        $this->info("Sinkronisasi massal selesai!");
        $this->info("Sukses: {$successCount}, Gagal: {$failedCount}");

        return $failedCount === 0 ? self::SUCCESS : self::FAILURE;
    }
}
