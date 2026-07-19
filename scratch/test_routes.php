<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;

echo "All registered route names:\n";
foreach (Route::getRoutes() as $route) {
    if ($route->getName()) {
        echo "- {$route->getName()} -> {$route->uri()} (" . implode(',', $route->methods()) . ")\n";
    }
}
