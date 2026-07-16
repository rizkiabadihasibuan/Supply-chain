<?php

namespace App\Helpers;

class SystemHelper
{
    /**
     * Format a simple standard response or message helper.
     */
    public static function formatLogMessage(string $apiName, string $message, string $level = 'info'): string
    {
        return sprintf("[%s][%s] %s", strtoupper($level), $apiName, $message);
    }
}
