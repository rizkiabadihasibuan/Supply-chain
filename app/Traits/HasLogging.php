<?php
namespace App\Traits;
use Illuminate\Support\Facades\Log;
/**
 * HasLogging – Trait untuk structured logging
 * TODO (Backend Phase): Gunakan pada Services dan Controllers
 */
trait HasLogging {
    protected function logInfo(string $msg, array $ctx = []): void { Log::channel('application')->info($msg, $ctx); }
    protected function logError(string $msg, array $ctx = []): void { Log::channel('application')->error($msg, $ctx); }
}
