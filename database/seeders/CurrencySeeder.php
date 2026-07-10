<?php

namespace Database\Seeders;

use App\Models\CurrencyCache;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rates = [
            [
                'from_currency' => 'USD',
                'to_currency' => 'EUR',
                'rate' => 0.921500,
                'volatility' => 1.25,
                'last_updated_at' => Carbon::now(),
            ],
            [
                'from_currency' => 'USD',
                'to_currency' => 'CNY',
                'rate' => 7.234000,
                'volatility' => 0.85,
                'last_updated_at' => Carbon::now(),
            ],
            [
                'from_currency' => 'USD',
                'to_currency' => 'IDR',
                'rate' => 16210.000000,
                'volatility' => 2.40,
                'last_updated_at' => Carbon::now(),
            ],
            [
                'from_currency' => 'USD',
                'to_currency' => 'AUD',
                'rate' => 1.502500,
                'volatility' => 1.65,
                'last_updated_at' => Carbon::now(),
            ],
            [
                'from_currency' => 'EUR',
                'to_currency' => 'IDR',
                'rate' => 17590.000000,
                'volatility' => 1.85,
                'last_updated_at' => Carbon::now(),
            ]
        ];

        foreach ($rates as $rateData) {
            CurrencyCache::updateOrCreate(
                [
                    'from_currency' => $rateData['from_currency'],
                    'to_currency' => $rateData['to_currency']
                ],
                $rateData
            );
        }
    }
}
