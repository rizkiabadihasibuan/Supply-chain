<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncCountriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync master data countries from REST Countries API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting sync for Countries...');
        
        // Use reliable REST Countries data mirror because v3.1 is deprecated
        $url = 'https://raw.githubusercontent.com/mledoze/countries/master/countries.json';
        $response = Http::withoutVerifying()->get($url);

        if ($response->failed()) {
            $this->error('Failed to fetch from REST Countries API.');
            Log::error('SyncCountriesCommand failed: ' . $response->body());
            return 1;
        }

        $countries = $response->json();
        $this->info('Fetched ' . count($countries) . ' countries. Syncing to database...');

        $bar = $this->output->createProgressBar(count($countries));
        $bar->start();

        foreach ($countries as $data) {
            try {
                if (empty($data['cca2'])) {
                    continue;
                }

                // 1. Handle Region
                $regionName = $data['region'] ?? 'Unknown';
                $region = Region::firstOrCreate(['name' => $regionName]);

                // 2. Handle Currency
                $currencyId = null;
                if (!empty($data['currencies']) && is_array($data['currencies'])) {
                    $currencyCode = array_key_first($data['currencies']);
                    $currencyData = $data['currencies'][$currencyCode] ?? [];
                    
                    $currency = Currency::firstOrCreate(
                        ['code' => $currencyCode],
                        [
                            'name' => $currencyData['name'] ?? $currencyCode,
                            'symbol' => $currencyData['symbol'] ?? null,
                            'exchange_rate_to_usd' => 1.00 // Default, to be synced later
                        ]
                    );
                    $currencyId = $currency->id;
                }

                // 3. Handle Country
                $languages = [];
                if (!empty($data['languages'])) {
                    $languages = array_values($data['languages']);
                }

                $lat = $data['latlng'][0] ?? null;
                $lng = $data['latlng'][1] ?? null;
                
                // Fallback to flagcdn if flags['png'] doesn't exist in the mirror dataset
                $flagUrl = $data['flags']['png'] ?? 'https://flagcdn.com/w320/' . strtolower($data['cca2']) . '.png';

                Country::updateOrCreate(
                    ['code' => $data['cca2']],
                    [
                        'name' => $data['name']['common'] ?? $data['cca2'],
                        'region_id' => $region->id,
                        'currency_id' => $currencyId,
                        'subregion' => $data['subregion'] ?? null,
                        'population' => $data['population'] ?? null,
                        'area' => $data['area'] ?? null,
                        'timezone' => $data['timezones'][0] ?? null,
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'flag_url' => $flagUrl,
                        'languages' => $languages,
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Failed to sync country ' . ($data['cca2'] ?? 'Unknown') . ': ' . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Countries synced successfully!');

        return 0;
    }
}
