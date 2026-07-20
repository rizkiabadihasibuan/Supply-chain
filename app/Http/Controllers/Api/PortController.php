<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\Contracts\PortServiceInterface;
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
     * Constructor for Dependency Injection
     *
     * @param PortServiceInterface $PortService
     */
    public function __construct(PortServiceInterface $PortService)
    {
        $this->PortService = $PortService;
    }

    /**
     * index method — Get all ports with country and risk data
     * GET /api/v1/ports
     */
    public function index(Request $request)
    {
        try {
            $query = Port::with(['country.riskScore', 'country.region']);

            // Optional filter by country
            if ($request->has('country_id')) {
                $query->where('country_id', $request->query('country_id'));
            }

            // Optional filter by country code
            if ($request->has('country_code')) {
                $query->whereHas('country', fn($q) => $q->where('code', strtoupper($request->query('country_code'))));
            }

            // Optional filter by size
            if ($request->has('size')) {
                $query->where('size', $request->query('size'));
            }

            $ports = $query->get();

            $result = $ports->map(function ($port) {
                return [
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
                        'risk_score' => (float) ($port->country->riskScore?->final_risk_score ?? 0.0),
                        'risk_level' => $port->country->riskScore?->risk_level ?? 'Not Calculated',
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Ports retrieved successfully',
                'data' => $result,
                'total' => $result->count(),
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