<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class SyncCurrencyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:sync 
                            {country? : Kode ISO negara yang ingin disinkronkan} 
                            {--force : Paksa refresh cache dan ambil dari API}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menyinkronkan data kurs mata uang negara di database lokal dengan Exchange Rate API';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\CurrencyService $currencyService)
    {
        $countryCode = $this->argument('country');
        $force = $this->option('force');

        if ($countryCode) {
            $countryCode = strtoupper(trim($countryCode));
            $this->info("Memulai sinkronisasi data kurs untuk negara: {$countryCode}...");

            try {
                $country = $currencyService->syncCountryCurrency($countryCode, $force);
                $this->info("Sukses! Data kurs negara '{$country->name}' ({$country->currency_code}: {$country->exchange_rate}) berhasil diperbarui.");
                return self::SUCCESS;
            } catch (\Exception $e) {
                $this->error("Gagal menyinkronkan data kurs negara '{$countryCode}': " . $e->getMessage());
                return self::FAILURE;
            }
        }

        // Sync all countries
        $countries = \App\Models\Country::all();
        if ($countries->isEmpty()) {
            $this->warn("Tidak ada data negara di database lokal untuk disinkronkan.");
            return self::SUCCESS;
        }

        $this->info("Menyinkronkan data kurs " . $countries->count() . " negara dari Exchange Rate API...");

        $bar = $this->output->createProgressBar($countries->count());
        $bar->start();

        $successCount = 0;
        $failedCount = 0;

        foreach ($countries as $country) {
            if (empty($country->currency_code)) {
                $failedCount++;
                $this->error("\nNegara {$country->code} tidak memiliki kode mata uang.");
                $bar->advance();
                continue;
            }

            try {
                $currencyService->syncCountryCurrency($country->code, $force);
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("\nGagal menyinkronkan data kurs {$country->code}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->line("");
        $this->info("Sinkronisasi data kurs massal selesai!");
        $this->info("Sukses: {$successCount}, Gagal: {$failedCount}");

        return $failedCount === 0 ? self::SUCCESS : self::FAILURE;
    }
}
