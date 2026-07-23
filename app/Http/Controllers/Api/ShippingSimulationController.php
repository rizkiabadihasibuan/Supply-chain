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

        // Generate strictly maritime waypoints (ocean corridors and sea straits)
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
                        'description' => 'Kapal kargo mengangkat jangkar dan bertolak melintasi koridor laut terbuka.'
                    ],
                    [
                        'step' => 3,
                        'title' => 'Pelayaran Jalur Laut Internasional',
                        'location' => 'Rute Navigasi Laut Lepas & Selat Maritim',
                        'status' => 'Pending',
                        'progress_pct' => 60,
                        'description' => 'Kapal berlayar melintasi selat maritim internasional dengan kecepatan jelajah 18 knots.'
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
     * Generate sea-only maritime waypoints navigating around landmasses via sea straits & ocean corridors.
     */
    private function generateMaritimeWaypoints(float $lat1, float $lng1, float $lat2, float $lng2, bool $isSame): array
    {
        if ($isSame) {
            return [
                [round($lat1, 5), round($lng1, 5)],
                [round($lat1 + 0.03, 5), round($lng1 + 0.03, 5)],
                [round($lat2, 5), round($lng2, 5)]
            ];
        }

        // Key Global Maritime Sea Junctions & Water Passages (Strictly Water Coords)
        $seaNodes = [
            'BALTIC_SEA'       => [56.0, 19.0],
            'NORTH_SEA'        => [54.0, 4.0],
            'ENGLISH_CHANNEL'  => [50.2, -0.5],
            'BISCAY'           => [45.0, -7.0],
            'GIBRALTAR'        => [35.9, -5.3],
            'MEDITERRANEAN'    => [34.0, 18.0],
            'SUEZ_CANAL'       => [29.9, 32.5],
            'RED_SEA'          => [22.0, 38.0],
            'BAB_EL_MANDEB'    => [12.5, 43.5],
            'ARABIAN_SEA'      => [12.0, 60.0],
            'INDIAN_OCEAN_MID' => [0.0, 75.0],
            'BAY_OF_BENGAL'    => [10.0, 87.0],
            'MALACCA_STRAIT'   => [2.5, 101.5],
            'SINGAPORE_STRAIT' => [1.25, 103.8],
            'SOUTH_CHINA_SEA'  => [14.0, 114.0],
            'TAIWAN_STRAIT'    => [23.5, 119.5],
            'EAST_CHINA_SEA'   => [29.0, 124.0],
            'PHILIPPINE_SEA'   => [16.0, 130.0],
            'PACIFIC_MID'      => [20.0, 175.0],
            'SUNDA_STRAIT'     => [-5.9, 105.8],
            'JAVA_SEA'         => [-5.0, 110.0],
            'LOMBOK_STRAIT'    => [-8.5, 115.7],
        ];

        $intermediateWaypoints = [];

        // Region checks
        $isEurope1 = ($lat1 > 35 && $lng1 < 40 && $lng1 > -25);
        $isEurope2 = ($lat2 > 35 && $lng2 < 40 && $lng2 > -25);
        $isAsia1   = ($lat1 > -11 && $lat1 < 65 && $lng1 > 60 && $lng1 < 150);
        $isAsia2   = ($lat2 > -11 && $lat2 < 65 && $lng2 > 60 && $lng2 < 150);
        $isSEA1    = ($lat1 > -11 && $lat1 < 20 && $lng1 > 90 && $lng1 < 140);
        $isSEA2    = ($lat2 > -11 && $lat2 < 20 && $lng2 > 90 && $lng2 < 140);
        $isEastAsia1 = ($lat1 > 15 && $lat1 < 50 && $lng1 > 110 && $lng1 < 145);
        $isEastAsia2 = ($lat2 > 15 && $lat2 < 50 && $lng2 > 110 && $lng2 < 145);

        // Case 1: Europe/Russia <-> SEA/Asia via Suez & Malacca
        if (($isEurope1 && $isAsia2) || ($isAsia1 && $isEurope2)) {
            $nodesForward = [
                $seaNodes['NORTH_SEA'],
                $seaNodes['ENGLISH_CHANNEL'],
                $seaNodes['BISCAY'],
                $seaNodes['GIBRALTAR'],
                $seaNodes['MEDITERRANEAN'],
                $seaNodes['SUEZ_CANAL'],
                $seaNodes['RED_SEA'],
                $seaNodes['BAB_EL_MANDEB'],
                $seaNodes['ARABIAN_SEA'],
                $seaNodes['BAY_OF_BENGAL'],
                $seaNodes['MALACCA_STRAIT'],
                $seaNodes['SINGAPORE_STRAIT']
            ];

            if ($isEurope2) {
                $nodesForward = array_reverse($nodesForward);
            }
            
            if ($isEastAsia2 && !$isEurope2) {
                $nodesForward[] = $seaNodes['SOUTH_CHINA_SEA'];
                $nodesForward[] = $seaNodes['TAIWAN_STRAIT'];
            } elseif ($isEastAsia1 && $isEurope2) {
                array_unshift($nodesForward, $seaNodes['TAIWAN_STRAIT'], $seaNodes['SOUTH_CHINA_SEA']);
            }

            $intermediateWaypoints = $nodesForward;
        }
        // Case 2: East Asia (China/Japan/Korea) <-> SEA (Indonesia/Singapore/Malaysia)
        elseif (($isEastAsia1 && $isSEA2) || ($isSEA1 && $isEastAsia2)) {
            $nodesForward = [
                $seaNodes['EAST_CHINA_SEA'],
                $seaNodes['TAIWAN_STRAIT'],
                $seaNodes['SOUTH_CHINA_SEA'],
                $seaNodes['SINGAPORE_STRAIT'],
                $seaNodes['JAVA_SEA']
            ];
            if ($isSEA1 && $isEastAsia2) {
                $nodesForward = array_reverse($nodesForward);
            }
            $intermediateWaypoints = $nodesForward;
        }
        // Case 3: Middle East <-> SEA
        elseif (($lng1 > 35 && $lng1 < 65 && $lat1 > 10 && $lat1 < 32) || ($lng2 > 35 && $lng2 < 65 && $lat2 > 10 && $lat2 < 32)) {
            $intermediateWaypoints = [
                $seaNodes['ARABIAN_SEA'],
                $seaNodes['INDIAN_OCEAN_MID'],
                $seaNodes['MALACCA_STRAIT'],
                $seaNodes['SINGAPORE_STRAIT']
            ];
        }
        // Case 4: Default Ocean Offset avoiding land center
        else {
            $midLat = ($lat1 + $lat2) / 2;
            $midLng = ($lng1 + $lng2) / 2;
            
            $dLat = $lat2 - $lat1;
            $dLng = $lng2 - $lng1;
            $curvatureFactor = 0.25;

            $offsetLat = -$dLng * $curvatureFactor;
            $offsetLng = $dLat * $curvatureFactor;

            $intermediateWaypoints[] = [$midLat + $offsetLat, $midLng + $offsetLng];
        }

        // Build full path chain: Origin -> Intermediate Sea Nodes -> Destination
        $pathChain = [];
        $pathChain[] = [$lat1, $lng1];
        foreach ($intermediateWaypoints as $node) {
            $pathChain[] = $node;
        }
        $pathChain[] = [$lat2, $lng2];

        // Smooth interpolate through all path chain nodes using multi-segment Bezier curves
        $finalWaypoints = [];
        $chainCount = count($pathChain);

        for ($k = 0; $k < $chainCount - 1; $k++) {
            $pA = $pathChain[$k];
            $pB = $pathChain[$k + 1];

            $segmentSteps = 8;
            for ($s = 0; $s < $segmentSteps; $s++) {
                if ($k > 0 && $s === 0) continue; // avoid duplicate points at junctions
                $t = $s / $segmentSteps;
                $lat = $pA[0] + ($pB[0] - $pA[0]) * $t;
                $lng = $pA[1] + ($pB[1] - $pA[1]) * $t;
                $finalWaypoints[] = [round($lat, 5), round($lng, 5)];
            }
        }

        // Force exact start and destination points
        $finalWaypoints[0] = [round($lat1, 5), round($lng1, 5)];
        $finalWaypoints[count($finalWaypoints) - 1] = [round($lat2, 5), round($lng2, 5)];

        return $finalWaypoints;
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
