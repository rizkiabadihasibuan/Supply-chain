<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\Contracts\PortServiceInterface;
use App\Services\WeatherService;
use App\Models\Port;
use App\Http\Resources\Port\PortResource;
use App\Http\Resources\Port\PortCollection;

class PortController extends BaseApiController
{
    /**
     * @var PortServiceInterface
     */
    protected $PortService;

    /**
     * @var WeatherService
     */
    protected $WeatherService;

    /**
     * Constructor for Dependency Injection
     */
    public function __construct(PortServiceInterface $PortService, WeatherService $WeatherService)
    {
        $this->PortService = $PortService;
        $this->WeatherService = $WeatherService;
    }

    /**
     * index method — Get all ports with country and risk data
     * GET /api/v1/ports
     */
    public function index(Request $request)
    {
        try {
            $query = Port::with(['country.riskScore', 'country.region']);

            if ($request->has('country_id')) {
                $query->where('country_id', $request->query('country_id'));
            }
            if ($request->has('country_code')) {
                $query->whereHas('country', fn($q) => $q->where('code', strtoupper($request->query('country_code'))));
            }
            if ($request->has('size')) {
                $query->where('size', $request->query('size'));
            }

            $ports = $query->get();

            // Bulk fetch weather using Http::pool to prevent N+1 API calls and make it real-time
            $weatherDataList = [];
            try {
                $responses = \Illuminate\Support\Facades\Http::pool(function (\Illuminate\Http\Client\Pool $pool) use ($ports) {
                    $requests = [];
                    foreach ($ports as $port) {
                        if ($port->latitude && $port->longitude) {
                            $requests[] = $pool->as((string)$port->id)->timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                                'latitude' => $port->latitude,
                                'longitude' => $port->longitude,
                                'current' => 'temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code,rain',
                            ]);
                        }
                    }
                    return $requests;
                });

                foreach ($responses as $portId => $response) {
                    if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                        $data = $response->json();
                        $current = $data['current'] ?? [];
                        if (!empty($current)) {
                            $weatherDataList[$portId] = [
                                'temp'        => round($current['temperature_2m'] ?? 25, 1),
                                'wind_speed'  => round($current['wind_speed_10m'] ?? 0, 1),
                                'rain'        => round($current['rain'] ?? 0, 1),
                                'description' => $this->WeatherService->mapWeatherCode($current['weather_code'] ?? -1),
                                'humidity'    => $current['relative_humidity_2m'] ?? 70,
                            ];
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Silently ignore bulk fetch errors to avoid breaking the dashboard
            }

            $result = $ports->map(function ($port) use ($weatherDataList) {
                $weather = $weatherDataList[$port->id] ?? null;

                return [
                    'id'          => $port->id,
                    'code'        => $port->code,
                    'name'        => $port->name,
                    'latitude'    => $port->latitude,
                    'longitude'   => $port->longitude,
                    'size'        => $port->size,
                    'type'        => $port->type,
                    'harbor_type' => $port->harbor_type,
                    'weather'     => $weather,
                    'country'     => $port->country ? [
                        'id'         => $port->country->id,
                        'name'       => $port->country->name,
                        'code'       => $port->country->code,
                        'flag_url'   => $port->country->flag_url,
                        'region'     => $port->country->region?->name,
                        'risk_score' => (float) ($port->country->riskScore?->final_risk_score ?? 0.0),
                        'risk_level' => $port->country->riskScore?->risk_level ?? 'Not Calculated',
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Ports retrieved successfully',
                'data'    => $result,
                'total'   => $result->count(),
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve ports', [$e->getMessage()], 500);
        }
    }

    /**
     * show method — Get single port detail by ID
     * GET /api/v1/ports/{id}
     */
    public function show($id)
    {
        try {
            $port = Port::with(['country.riskScore', 'country.region', 'country.currency'])->find($id);

            if (!$port) {
                return $this->sendError('Port not found', [], 404);
            }

            $result = [
                'id' => $port->id,
                'code' => $port->code,
                'name' => $port->name,
                'latitude' => $port->latitude,
                'longitude' => $port->longitude,
                'size' => $port->size,
                'type' => $port->type,
                'harbor_type' => $port->harbor_type,
                'country' => $port->country ? [
                    'id' => $port->country->id,
                    'name' => $port->country->name,
                    'code' => $port->country->code,
                    'flag_url' => $port->country->flag_url,
                    'region' => $port->country->region?->name,
                    'currency' => $port->country->currency?->code,
                    'risk_score' => (float) ($port->country->riskScore?->final_risk_score ?? 0.0),
                    'risk_level' => $port->country->riskScore?->risk_level ?? 'Not Calculated',
                ] : null,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Port detail retrieved successfully',
                'data' => $result,
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve port detail', [$e->getMessage()], 500);
        }
    }

    /**
     * search method — Search ports by name or country
     * GET /api/v1/ports/search?q=Singapore
     */
    public function search(Request $request)
    {
        try {
            $keyword = $request->input('q', $request->input('query', ''));

            $ports = Port::with(['country.riskScore'])
                ->where(function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('code', 'like', '%' . strtoupper($keyword) . '%')
                        ->orWhereHas('country', fn($q) => $q->where('name', 'like', '%' . $keyword . '%'));
                })
                ->limit(50)
                ->get();

            $result = $ports->map(function ($port) {
                return [
                    'id' => $port->id,
                    'code' => $port->code,
                    'name' => $port->name,
                    'latitude' => $port->latitude,
                    'longitude' => $port->longitude,
                    'size' => $port->size,
                    'country' => $port->country ? [
                        'name' => $port->country->name,
                        'code' => $port->country->code,
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Ports search results',
                'data' => $result,
                'total' => $result->count(),
                'query' => $keyword,
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to search ports', [$e->getMessage()], 500);
        }
    }

}