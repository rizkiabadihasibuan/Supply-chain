<?php

namespace App\Integrations\Support;

use Illuminate\Support\Facades\Http;

class ApiHealthChecker
{
    public function check(string $url): bool
    {
        try {
            $response = Http::timeout(5)->get($url);
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}