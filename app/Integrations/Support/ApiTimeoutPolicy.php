<?php

namespace App\Integrations\Support;

class ApiTimeoutPolicy
{
    public function getDefaultTimeout(): int
    {
        return config('api.timeout', 3);
    }
}