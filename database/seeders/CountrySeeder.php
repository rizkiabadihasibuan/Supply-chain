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
                'iso2' => 'DE',
                'iso3' => 'DEU',
                'currency_code' => 'EUR',
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'region' => 'Europe',
                'subregion' => 'Western Europe',
                'capital' => 'Berlin',
                'language' => 'German',
                'latitude' => 51.16569100,
                'longitude' => 10.45152600,
                'population' => 84000000,
                'area' => 357114.0,
                'timezone' => 'UTC+01:00',
                'flag_url' => 'https://flagcdn.com/de.svg',
                'gdp' => 4400000000000,
                'inflation' => 2.1,
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
                'iso2' => 'CN',
                'iso3' => 'CHN',
                'currency_code' => 'CNY',
                'currency_name' => 'Renminbi (Yuan)',
                'currency_symbol' => '¥',
                'region' => 'Asia',
                'subregion' => 'Eastern Asia',
                'capital' => 'Beijing',
                'language' => 'Chinese',
                'latitude' => 35.86166000,
                'longitude' => 104.19539700,
                'population' => 1410000000,
                'area' => 9596961.0,
                'timezone' => 'UTC+08:00',
                'flag_url' => 'https://flagcdn.com/cn.svg',
                'gdp' => 17800000000000,
                'inflation' => 0.3,
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
                'iso2' => 'ID',
                'iso3' => 'IDN',
                'currency_code' => 'IDR',
                'currency_name' => 'Indonesian Rupiah',
                'currency_symbol' => 'Rp',
                'region' => 'Southeast Asia',
                'subregion' => 'Maritime Southeast Asia',
                'capital' => 'Jakarta',
                'language' => 'Indonesian',
                'latitude' => -0.78927500,
                'longitude' => 113.92132700,
                'population' => 277000000,
                'area' => 1904569.0,
                'timezone' => 'UTC+07:00, UTC+08:00, UTC+09:00',
                'flag_url' => 'https://flagcdn.com/id.svg',
                'gdp' => 1370000000000,
                'inflation' => 2.8,
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
                'iso2' => 'AU',
                'iso3' => 'AUS',
                'currency_code' => 'AUD',
                'currency_name' => 'Australian Dollar',
                'currency_symbol' => '$',
                'region' => 'Oceania',
                'subregion' => 'Australia and New Zealand',
                'capital' => 'Canberra',
                'language' => 'English',
                'latitude' => -25.27439800,
                'longitude' => 133.77513600,
                'population' => 26000000,
                'area' => 7692024.0,
                'timezone' => 'UTC+08:00 to UTC+10:30',
                'flag_url' => 'https://flagcdn.com/au.svg',
                'gdp' => 1700000000000,
                'inflation' => 3.6,
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
