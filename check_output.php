<?php
require 'vendor/autoload.php';
ob_start();
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Illuminate\Http\Request::create('/api/v1/dashboard', 'GET'));
$output = ob_get_clean();
echo "Stray output length: " . strlen($output) . PHP_EOL;
echo "Hex: " . bin2hex($output) . PHP_EOL;
echo "String: " . $output . PHP_EOL;
