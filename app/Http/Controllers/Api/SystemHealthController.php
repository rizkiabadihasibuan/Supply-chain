<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class SystemHealthController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $dbStatus = true;
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = false;
        }

        $cacheStatus = true;
        try {
            Cache::set('health_check', 'ok', 10);
            if (Cache::get('health_check') !== 'ok') {
                $cacheStatus = false;
            }
        } catch (\Exception $e) {
            $cacheStatus = false;
        }

        $pendingJobs = 0;
        $failedJobs = 0;
        try {
            $pendingJobs = DB::table('jobs')->count();
            $failedJobs = DB::table('failed_jobs')->count();
        } catch (\Exception $e) {
            // tables missing
        }

        $data = [
            'status' => ($dbStatus && $cacheStatus) ? 'Operational' : 'Degraded',
            'database' => $dbStatus ? 'Connected' : 'Disconnected',
            'cache' => $cacheStatus ? 'Connected' : 'Disconnected',
            'queue' => [
                'connection' => config('queue.default'),
                'pending_jobs' => $pendingJobs,
                'failed_jobs' => $failedJobs,
            ],
            'apis' => [
                'weather' => [
                    'status' => Cache::get('api_status_weather', 'Unknown'),
                    'last_sync' => Cache::get('api_last_sync_weather', 'Never'),
                ],
                'exchange_rate' => [
                    'status' => Cache::get('api_status_exchange_rate', 'Unknown'),
                    'last_sync' => Cache::get('api_last_sync_exchange_rate', 'Never'),
                ],
                'world_bank' => [
                    'status' => Cache::get('api_status_world_bank', 'Unknown'),
                    'last_sync' => Cache::get('api_last_sync_world_bank', 'Never'),
                ],
                'countries' => [
                    'status' => Cache::get('api_status_countries', 'Unknown'),
                    'last_sync' => Cache::get('api_last_sync_countries', 'Never'),
                ],
                'news' => [
                    'status' => Cache::get('api_status_news', 'Unknown'),
                    'last_sync' => Cache::get('api_last_sync_news', 'Never'),
                ],
                'port' => [
                    'status' => Cache::get('api_status_port', 'Unknown'),
                    'last_sync' => Cache::get('api_last_sync_port', 'Never'),
                ],
            ],
            'timestamp' => now()->toIso8601String()
        ];

        if ($data['status'] !== 'Operational') {
            return response()->json([
                'success' => false,
                'message' => 'System is degraded.',
                'data' => $data,
                'timestamp' => now()->toIso8601String()
            ], 503);
        }

        return $this->sendSuccess('System health metrics retrieved successfully', $data);
    }
}
