<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;

// Get all defined route names
$definedRoutes = [];
foreach (Route::getRoutes() as $route) {
    if ($route->getName()) {
        $definedRoutes[$route->getName()] = true;
    }
}

echo "Defined Routes Count: " . count($definedRoutes) . "\n";

// Scan views
$viewDir = __DIR__ . '/../resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewDir));
$errors = [];

foreach ($files as $file) {
    if ($file->isDir() || $file->getExtension() !== 'blade' && !str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }
    
    $content = file_get_contents($file->getPathname());
    
    // Regex to match route('name') or route("name")
    // Match route('some.name') or route('some.name', [...])
    preg_match_all('/route\(\s*[\'"]([a-zA-Z0-9_\-\.\:\/]+)[\'"]/', $content, $matches);
    
    if (!empty($matches[1])) {
        foreach ($matches[1] as $routeName) {
            // Check if routeName is defined
            if (!isset($definedRoutes[$routeName])) {
                $relative = str_replace(realpath(__DIR__ . '/..'), '', $file->getPathname());
                $errors[] = [
                    'file' => $relative,
                    'route' => $routeName
                ];
            }
        }
    }
}

if (empty($errors)) {
    echo "SUCCESS: No undefined route names found in Blade files!\n";
} else {
    echo "FOUND UNDEFINED ROUTES IN VIEWS:\n";
    foreach ($errors as $err) {
        echo "- In File: {$err['file']} -> Route [{$err['route']}] is undefined!\n";
    }
}
