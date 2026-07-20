<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;

echo "Before: " . Country::count() . "\n";

$r = Region::firstOrCreate(['name' => 'Test Region']);
$c = Currency::firstOrCreate(['code' => 'TST'], ['name' => 'Test Currency', 'exchange_rate_to_usd' => 1.0]);

try {
    Country::updateOrCreate(
        ['code' => 'XX'],
        [
            'name' => 'Test Country',
            'region_id' => $r->id,
            'currency_id' => $c->id,
        ]
    );
    echo "Success!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "After: " . Country::count() . "\n";
