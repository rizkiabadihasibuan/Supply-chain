<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rates = [
            'USD_EUR' => ['rate' => 0.9215, 'volatility' => 1.25],
            'USD_CNY' => ['rate' => 7.234, 'volatility' => 0.85],
            'USD_IDR' => ['rate' => 16210.0, 'volatility' => 2.40],
            'USD_AUD' => ['rate' => 1.5025, 'volatility' => 1.65],
            'EUR_IDR' => ['rate' => 17590.0, 'volatility' => 1.85],
        ];

        foreach ($rates as $key => $data) {
            Cache::forever("currency_rate_{$key}", $data);
        }
    }
}
