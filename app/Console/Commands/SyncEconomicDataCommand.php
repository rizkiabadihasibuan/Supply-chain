<?php

namespace App\Console\Commands;

use App\Jobs\SyncEconomicDataJob;
use App\Models\Country;
use App\Services\WorldBankService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class SyncEconomicDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'economic:sync 
                            {country? : Kode ISO negara yang ingin disinkronkan} 
                            {--force : Paksa refresh cache dan ambil dari API} 
                            {--queue : Jalankan sinkronisasi melalui antrean (queue)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menyinkronkan data metrik ekonomi negara di database lokal dengan World Bank API';

    /**
     * Execute the console command.
     */
    public function handle(WorldBankService $worldBankService)
    {
        $countryCode = $this->argument('country');
        $force = $this->option('force');
        $queue = $this->option('queue');

        if ($countryCode) {
            $countryCode = strtoupper(trim($countryCode));
            $this->info("Memulai sinkronisasi data ekonomi untuk negara: {$countryCode}...");

            if ($queue) {
                SyncEconomicDataJob::dispatch($countryCode, $force);
                $this->info("Job sinkronisasi ekonomi untuk '{$countryCode}' telah dikirim ke antrean.");

                return self::SUCCESS;
            }

            try {
                $country = $worldBankService->syncCountryEconomicData($countryCode, $force);
                $this->info("Sukses! Data ekonomi negara '{$country->name}' ({$country->code}) berhasil diperbarui.");

                return self::SUCCESS;
            } catch (\Exception $e) {
                $this->error("Gagal menyinkronkan data ekonomi negara '{$countryCode}': ".$e->getMessage());

                return self::FAILURE;
            }
        }

        // Sync all countries
        $countries = Country::all();
        if ($countries->isEmpty()) {
            $this->warn('Tidak ada data negara di database lokal untuk disinkronkan.');

            return self::SUCCESS;
        }

        $this->info('Menyinkronkan data ekonomi '.$countries->count().' negara dari World Bank API...');

        if ($queue) {
            foreach ($countries as $country) {
                SyncEconomicDataJob::dispatch($country->code, $force);
            }
            $this->info('Seluruh job sinkronisasi ekonomi negara telah dikirim ke antrean.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($countries->count());
        $bar->start();

        $successCount = 0;
        $failedCount = 0;

        foreach ($countries as $country) {
            try {
                $worldBankService->syncCountryEconomicData($country->code, $force);
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("\nGagal menyinkronkan data ekonomi {$country->code}: ".$e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->line('');
        $this->info('Sinkronisasi data ekonomi massal selesai!');
        $this->info("Sukses: {$successCount}, Gagal: {$failedCount}");

        return $failedCount === 0 ? self::SUCCESS : self::FAILURE;
    }
}
