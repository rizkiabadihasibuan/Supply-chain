<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Port;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingSimulationController extends Controller
{
    /**
     * Calculate sea route and simulation waypoints between origin country & destination country ports.
     */
    public function calculateRoute(Request $request): JsonResponse
    {
        $request->validate([
            'origin_country_id'      => 'required|integer|exists:countries,id',
            'destination_country_id' => 'required|integer|exists:countries,id',
            'package_name'           => 'nullable|string|max:100',
            'cargo_type'             => 'nullable|string|max:50',
        ]);

        $originCountryId = (int) $request->input('origin_country_id');
        $destCountryId   = (int) $request->input('destination_country_id');
        $packageName     = trim((string) $request->input('package_name', 'Paket Logistik Express'));
        if (empty($packageName)) {
            $packageName = 'Paket Logistik Express';
        }
        $cargoType       = $request->input('cargo_type', 'Container Cargo');

        $originCountry = Country::with(['ports', 'riskScore'])->find($originCountryId);
        $destCountry   = Country::with(['ports', 'riskScore'])->find($destCountryId);

        if (!$originCountry || !$destCountry) {
            return response()->json([
                'success' => false,
                'message' => 'Negara tidak ditemukan.'
            ], 404);
        }

        // Get origin port or synthesize coordinates from country lat/lng
        $originPort = $originCountry->ports->first();
        $originLat  = (float) ($originPort?->latitude ?? $originCountry->latitude ?? 0.0);
        $originLng  = (float) ($originPort?->longitude ?? $originCountry->longitude ?? 0.0);
        $originPortName = $originPort?->name ?? ('Pelabuhan Utama ' . $originCountry->name);
        $originPortCode = $originPort?->code ?? (strtoupper(substr($originCountry->code, 0, 2)) . 'PRT');

        // Get destination port
        $destPort = $destCountry->ports->first();
        $destLat  = (float) ($destPort?->latitude ?? $destCountry->latitude ?? 0.0);
        $destLng  = (float) ($destPort?->longitude ?? $destCountry->longitude ?? 0.0);
        $destPortName = $destPort?->name ?? ('Pelabuhan Utama ' . $destCountry->name);
        $destPortCode = $destPort?->code ?? (strtoupper(substr($destCountry->code, 0, 2)) . 'PRT');

        // Check if origin & dest are same
        $isSameCountry = ($originCountryId === $destCountryId);

        // Generate maritime waypoints
        $waypoints = $this->generateMaritimeWaypoints($originLat, $originLng, $destLat, $destLng, $isSameCountry);

        // Calculate total distance in Nautical Miles
        $totalDistanceNm = $this->calculateTotalDistance($waypoints);
        
        // Estimated transit speed average 18 knots (nautical miles per hour)
        $speedKnots = 18;
        $estimatedHours = max(1, (int) round($totalDistanceNm / $speedKnots));
        $estimatedDays = round($estimatedHours / 24, 1);

        // Risk levels
        $originRisk = (float) ($originCountry->riskScore?->final_risk_score ?? 3.0);
        $destRisk   = (float) ($destCountry->riskScore?->final_risk_score ?? 3.0);
        $avgRiskScore = round(($originRisk + $destRisk) / 2, 1);

        $riskCategory = 'Rendah';
        if ($avgRiskScore > 6.5) {
            $riskCategory = 'Tinggi';
        } elseif ($avgRiskScore > 4.0) {
            $riskCategory = 'Sedang';
        }

        // Generate tracking ID
        $trackingId = 'SP-LOG-' . strtoupper(substr(md5($originCountry->code . $destCountry->code . time()), 0, 8));

        return response()->json([
            'success' => true,
            'data'    => [
                'tracking_id'   => $trackingId,
                'package_name'  => $packageName,
                'cargo_type'    => $cargoType,
                'is_same_country' => $isSameCountry,
                'origin'        => [
                    'country_id'   => $originCountry->id,
                    'country_name' => $originCountry->name,
                    'country_code' => $originCountry->code,
                    'flag_url'     => $originCountry->flag_url,
                    'port_name'    => $originPortName,
                    'port_code'    => $originPortCode,
                    'lat'          => $originLat,
                    'lng'          => $originLng,
                    'risk_score'   => $originRisk,
                ],
                'destination'   => [
                    'country_id'   => $destCountry->id,
                    'country_name' => $destCountry->name,
                    'country_code' => $destCountry->code,
                    'flag_url'     => $destCountry->flag_url,
                    'port_name'    => $destPortName,
                    'port_code'    => $destPortCode,
                    'lat'          => $destLat,
                    'lng'          => $destLng,
                    'risk_score'   => $destRisk,
                ],
                'route' => [
                    'waypoints'       => $waypoints,
                    'distance_nm'     => $totalDistanceNm,
                    'distance_km'     => round($totalDistanceNm * 1.852),
                    'estimated_hours' => $estimatedHours,
                    'estimated_days'  => $estimatedDays,
                    'avg_speed_knots' => $speedKnots,
                    'route_risk_score' => $avgRiskScore,
                    'route_risk_level' => $riskCategory,
                ],
                'stages' => [
                    [
                        'step' => 1,
                        'title' => 'Pemuatan di Pelabuhan Asal',
                        'location' => $originPortName . ' (' . $originCountry->name . ')',
                        'status' => 'Pending',
                        'progress_pct' => 0,
                        'description' => 'Paket dimasukkan ke dalam kontainer kargo di ' . $originPortName . '.'
                    ],
                    [
                        'step' => 2,
                        'title' => 'Keberangkatan Kapal Kargo',
                        'location' => 'Perairan ' . $originCountry->name,
                        'status' => 'Pending',
                        'progress_pct' => 25,
                        'description' => 'Kapal kargo mengangkat jangkar dan bertolak ke perairan terbuka.'
                    ],
                    [
                        'step' => 3,
                        'title' => 'Transit Pelayaran Internasional',
                        'location' => 'Rute Maritim Laut Lepas',
                        'status' => 'Pending',
                        'progress_pct' => 60,
                        'description' => 'Kapal berlayar melintasi waypoints maritim dengan kecepatan jelajah 18 knots.'
                    ],
                    [
                        'step' => 4,
                        'title' => 'Berlabuh di Pelabuhan Tujuan',
                        'location' => $destPortName . ' (' . $destCountry->name . ')',
                        'status' => 'Pending',
                        'progress_pct' => 90,
                        'description' => 'Kapal tiba dan berlabuh tepat di dermaga ' . $destPortName . '.'
                    ],
                    [
                        'step' => 5,
                        'title' => 'Bongkar Muat & Paket Terkirim',
                        'location' => $destPortName . ', ' . $destCountry->name,
                        'status' => 'Pending',
                        'progress_pct' => 100,
                        'description' => 'Paket berhasil dibongkar dan diserahkan kepada pihak penerima di pelabuhan tujuan.'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Generate intermediate maritime waypoints for curved arc path.
     */
    private function generateMaritimeWaypoints(float $lat1, float $lng1, float $lat2, float $lng2, bool $isSame): array
    {
        if ($isSame) {
            // Offset small arc for same location
            return [
                [round($lat1, 5), round($lng1, 5)],
                [round($lat1 + 0.03, 5), round($lng1 + 0.03, 5)],
                [round($lat2, 5), round($lng2, 5)]
            ];
        }

        $numPoints = 30; // 30 points for smooth ship animation
        $waypoints = [];

        // Great circle / curve interpolation with slight curvature for sea route aesthetic
        $midLat = ($lat1 + $lat2) / 2;
        $midLng = ($lng1 + $lng2) / 2;

        // Perpendicular offset for curved sea route
        $dLat = $lat2 - $lat1;
        $dLng = $lng2 - $lng1;
        $curvatureFactor = 0.12; // arc curvature

        $offsetLat = -$dLng * $curvatureFactor;
        $offsetLng = $dLat * $curvatureFactor;

        $controlLat = $midLat + $offsetLat;
        $controlLng = $midLng + $offsetLng;

        for ($i = 0; $i <= $numPoints; $i++) {
            $t = $i / $numPoints;
            // Quadratic Bezier Curve: B(t) = (1-t)^2 * P0 + 2(1-t)t * P1 + t^2 * P2
            $lat = (1 - $t) * (1 - $t) * $lat1 + 2 * (1 - $t) * $t * $controlLat + $t * $t * $lat2;
            $lng = (1 - $t) * (1 - $t) * $lng1 + 2 * (1 - $t) * $t * $controlLng + $t * $t * $lng2;

            $waypoints[] = [round($lat, 5), round($lng, 5)];
        }

        // Force first point to exact origin and last point to EXACT destination
        $waypoints[0] = [round($lat1, 5), round($lng1, 5)];
        $waypoints[count($waypoints) - 1] = [round($lat2, 5), round($lng2, 5)];

        return $waypoints;
    }

    /**
     * Calculate total nautical miles along waypoints using Haversine formula.
     */
    private function calculateTotalDistance(array $waypoints): float
    {
        $totalKm = 0;
        $count = count($waypoints);

        for ($i = 0; $i < $count - 1; $i++) {
            $p1 = $waypoints[$i];
            $p2 = $waypoints[$i + 1];
            $totalKm += $this->haversineGreatCircleDistance($p1[0], $p1[1], $p2[0], $p2[1]);
        }

        // Convert km to nautical miles (1 km = 0.539957 NM)
        return round($totalKm * 0.539957, 1);
    }

    private function haversineGreatCircleDistance(float $latitudeFrom, float $longitudeFrom, float $latitudeTo, float $longitudeTo, float $earthRadius = 6371): float
    {
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo   = deg2rad($latitudeTo);
        $lonTo   = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            
        return $angle * $earthRadius;
    }
}
