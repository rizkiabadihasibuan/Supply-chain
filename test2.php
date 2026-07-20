<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$r = Illuminate\Support\Facades\Http::withoutVerifying()->get('https://restcountries.com/v3.1/all');
$data = $r->json();
echo "Type: " . gettype($data) . "\n";
echo "Count: " . count($data) . "\n";
if (is_array($data)) {
    echo "Keys: " . implode(", ", array_keys($data)) . "\n";
    if (isset($data[0])) {
        echo "First item keys: " . implode(", ", array_keys($data[0])) . "\n";
    }
}
