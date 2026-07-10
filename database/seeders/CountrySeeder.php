<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Port;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'code' => 'DE',
                'name' => 'Germany',
                'currency_code' => 'EUR',
                'currency_name' => 'Euro',
                'region' => 'Europe',
                'language' => 'German',
                'gdp' => 4400000000000,
                'inflation' => 2.1,
                'population' => 84000000,
                'current_weather_temp' => 18.5,
                'current_weather_condition' => 'Partly Cloudy',
                'ports' => [
                    [
                        'port_code' => 'DEHAM',
                        'name' => 'Port of Hamburg',
                        'latitude' => 53.54000000,
                        'longitude' => 9.92000000,
                        'waiting_time_hours' => 12,
                        'congestion_rate' => 35.5,
                    ],
                    [
                        'port_code' => 'DEBRE',
                        'name' => 'Port of Bremen',
                        'latitude' => 53.12000000,
                        'longitude' => 8.71000000,
                        'waiting_time_hours' => 8,
                        'congestion_rate' => 15.0,
                    ]
                ]
            ],
            [
                'code' => 'CN',
                'name' => 'China',
                'currency_code' => 'CNY',
                'currency_name' => 'Renminbi (Yuan)',
                'region' => 'Asia',
                'language' => 'Chinese',
                'gdp' => 17800000000000,
                'inflation' => 0.3,
                'population' => 1410000000,
                'current_weather_temp' => 27.2,
                'current_weather_condition' => 'Clear Sky',
                'ports' => [
                    [
                        'port_code' => 'CNSHG',
                        'name' => 'Port of Shanghai',
                        'latitude' => 30.62000000,
                        'longitude' => 122.06000000,
                        'waiting_time_hours' => 18,
                        'congestion_rate' => 55.2,
                    ],
                    [
                        'port_code' => 'CNNSA',
                        'name' => 'Port of Nansha',
                        'latitude' => 22.75000000,
                        'longitude' => 113.60000000,
                        'waiting_time_hours' => 14,
                        'congestion_rate' => 42.1,
                    ]
                ]
            ],
            [
                'code' => 'ID',
                'name' => 'Indonesia',
                'currency_code' => 'IDR',
                'currency_name' => 'Indonesian Rupiah',
                'region' => 'Southeast Asia',
                'language' => 'Indonesian',
                'gdp' => 1370000000000,
                'inflation' => 2.8,
                'population' => 277000000,
                'current_weather_temp' => 31.0,
                'current_weather_condition' => 'Heavy Rain',
                'ports' => [
                    [
                        'port_code' => 'IDTPP',
                        'name' => 'Port of Tanjung Priok',
                        'latitude' => -6.10000000,
                        'longitude' => 106.88000000,
                        'waiting_time_hours' => 24,
                        'congestion_rate' => 68.0,
                    ],
                    [
                        'port_code' => 'IDSUB',
                        'name' => 'Port of Tanjung Perak',
                        'latitude' => -7.20000000,
                        'longitude' => 112.73000000,
                        'waiting_time_hours' => 16,
                        'congestion_rate' => 45.3,
                    ]
                ]
            ],
            [
                'code' => 'AU',
                'name' => 'Australia',
                'currency_code' => 'AUD',
                'currency_name' => 'Australian Dollar',
                'region' => 'Oceania',
                'language' => 'English',
                'gdp' => 1700000000000,
                'inflation' => 3.6,
                'population' => 26000000,
                'current_weather_temp' => 15.8,
                'current_weather_condition' => 'Strong Winds',
                'ports' => [
                    [
                        'port_code' => 'AUSYD',
                        'name' => 'Port of Sydney',
                        'latitude' => -33.86000000,
                        'longitude' => 151.21000000,
                        'waiting_time_hours' => 6,
                        'congestion_rate' => 12.8,
                    ],
                    [
                        'port_code' => 'AUMEL',
                        'name' => 'Port of Melbourne',
                        'latitude' => -37.82000000,
                        'longitude' => 144.93000000,
                        'waiting_time_hours' => 10,
                        'congestion_rate' => 22.0,
                    ]
                ]
            ]
        ];

        foreach ($countries as $cData) {
            $ports = $cData['ports'];
            unset($cData['ports']);

            $country = Country::updateOrCreate(
                ['code' => $cData['code']],
                $cData
            );

            foreach ($ports as $pData) {
                Port::updateOrCreate(
                    ['port_code' => $pData['port_code']],
                    array_merge($pData, ['country_id' => $country->id])
                );
            }
        }
    }
}
