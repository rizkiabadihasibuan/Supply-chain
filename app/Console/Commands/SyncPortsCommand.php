<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Port;
use App\Models\Country;
use Faker\Factory as Faker;

class SyncPortsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ports:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync global ports data (Simulated integration with World Port Index)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting port synchronization...');
        
        $countries = Country::all();
        if ($countries->isEmpty()) {
            $this->error('No countries found. Please seed countries first.');
            return 1;
        }

        $this->info('Fetching data from World Port Index API...');
        
        try {
            // Initiate real API client instead of Faker
            $portApiClient = new \App\Integrations\WorldPort\PortApiClient();
            $totalSynced = 0;
            
            $bar = $this->output->createProgressBar(count($countries));
            $bar->start();

            foreach ($countries as $country) {
                // Real API fetch for each country
                $apiResponse = $portApiClient->searchPorts('', $country->name);
                
                // If the API returns valid data, we map and save it
                if (is_array($apiResponse) && !empty($apiResponse)) {
                    foreach (array_slice($apiResponse, 0, 10) as $portData) {
                        // Safe extraction from API response
                        $portName = $portData['port_name'] ?? $portData['name'] ?? ('Port of ' . $country->name);
                        $lat = $portData['latitude'] ?? $portData['lat'] ?? 0;
                        $lng = $portData['longitude'] ?? $portData['lng'] ?? 0;
                        $code = $portData['index_number'] ?? $portData['code'] ?? substr(md5(uniqid()), 0, 5);
                        
                        Port::updateOrCreate(
                            [
                                'code' => strtoupper($code),
                            ],
                            [
                                'country_id' => $country->id,
                                'name' => $portName,
                                'latitude' => $lat,
                                'longitude' => $lng,
                                'size' => $portData['harborsize'] ?? 'Medium',
                                'type' => 'Seaport',
                                'harbor_type' => $portData['harbortype'] ?? 'Coastal Natural',
                            ]
                        );
                        
                        $totalSynced++;
                    }
                }
                
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
            
            if ($totalSynced > 0) {
                $this->info("Successfully synced {$totalSynced} real ports from World Port Index API!");
            } else {
                $this->warn("Successfully connected to API, but no port records were found or returned for the monitored countries.");
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to sync ports from API: ' . $e->getMessage());
            return 1;
        }
    }
}
