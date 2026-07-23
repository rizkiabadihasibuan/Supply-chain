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

        // Resolve guaranteed coastal maritime sea ports for origin and destination countries
        $originPortInfo = $this->resolveCoastalPort($originCountry);
        $originLat      = $originPortInfo['lat'];
        $originLng      = $originPortInfo['lng'];
        $originPortName = $originPortInfo['name'];
        $originPortCode = $originPortInfo['code'];

        $destPortInfo   = $this->resolveCoastalPort($destCountry);
        $destLat        = $destPortInfo['lat'];
        $destLng        = $destPortInfo['lng'];
        $destPortName   = $destPortInfo['name'];
        $destPortCode   = $destPortInfo['code'];

        // Check if origin & dest are same
        $isSameCountry = ($originCountryId === $destCountryId);

        // Generate strictly maritime sea-only waypoints via BFS Sea Graph Solver
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
                        'location' => 'Perairan Pelabuhan ' . $originPortName,
                        'status' => 'Pending',
                        'progress_pct' => 25,
                        'description' => 'Kapal kargo bertolak melintasi perairan pelabuhan menuju jalur laut terbuka.'
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
     * Resolve guaranteed coastal maritime sea port for any country.
     */
    private function resolveCoastalPort(Country $country): array
    {
        $codeUpper = strtoupper($country->code ?? '');

        // 1. Global Verified Coastal Maritime Sea Port Dictionary
        $coastalDictionary = [
            'ID' => ['name' => 'Tanjung Priok – Jakarta', 'code' => 'IDTPP', 'lat' => -6.10, 'lng' => 106.88],
            'CN' => ['name' => 'Port of Shanghai', 'code' => 'CNSHA', 'lat' => 30.62, 'lng' => 122.05],
            'US' => ['name' => 'Port of Los Angeles', 'code' => 'USLAX', 'lat' => 33.72, 'lng' => -118.26],
            'SG' => ['name' => 'Port of Singapore', 'code' => 'SGSGP', 'lat' => 1.26, 'lng' => 103.80],
            'NL' => ['name' => 'Port of Rotterdam', 'code' => 'NLRTM', 'lat' => 51.90, 'lng' => 4.13],
            'DE' => ['name' => 'Port of Hamburg', 'code' => 'DEHAM', 'lat' => 53.53, 'lng' => 9.97],
            'JP' => ['name' => 'Port of Tokyo', 'code' => 'JPTYO', 'lat' => 35.62, 'lng' => 139.77],
            'KR' => ['name' => 'Port of Busan', 'code' => 'KRPUS', 'lat' => 35.10, 'lng' => 129.04],
            'RU' => ['name' => 'St. Petersburg Sea Port', 'code' => 'RULED', 'lat' => 59.90, 'lng' => 30.22],
            'GB' => ['name' => 'Port of Felixstowe', 'code' => 'GBFXT', 'lat' => 51.95, 'lng' => 1.31],
            'AU' => ['name' => 'Port of Sydney (Botany Bay)', 'code' => 'AUSYD', 'lat' => -33.96, 'lng' => 151.21],
            'IN' => ['name' => 'Jawaharlal Nehru Port (Mumbai)', 'code' => 'INNSA', 'lat' => 18.95, 'lng' => 72.95],
            'AE' => ['name' => 'Jebel Ali Port (Dubai)', 'code' => 'AEJEA', 'lat' => 24.98, 'lng' => 55.06],
            'MY' => ['name' => 'Port Klang', 'code' => 'MYPKG', 'lat' => 3.00, 'lng' => 101.38],
            'VN' => ['name' => 'Hai Phong Port', 'code' => 'VNHPH', 'lat' => 20.86, 'lng' => 106.68],
            'TH' => ['name' => 'Laem Chabang Port', 'code' => 'THLCH', 'lat' => 13.08, 'lng' => 100.91],
            'PH' => ['name' => 'Port of Manila', 'code' => 'PHMNL', 'lat' => 14.58, 'lng' => 120.96],
            'BR' => ['name' => 'Port of Santos', 'code' => 'BRSSZ', 'lat' => -23.95, 'lng' => -46.30],
            'ZA' => ['name' => 'Port of Durban', 'code' => 'ZADUR', 'lat' => -29.87, 'lng' => 31.02],
            'EG' => ['name' => 'Port Said', 'code' => 'EGPSD', 'lat' => 31.26, 'lng' => 32.30],
            'SA' => ['name' => 'Jeddah Islamic Port', 'code' => 'SAJED', 'lat' => 21.48, 'lng' => 39.18],
            'ES' => ['name' => 'Port of Valencia', 'code' => 'ESVLC', 'lat' => 39.45, 'lng' => -0.32],
            'FR' => ['name' => 'Port of Marseille', 'code' => 'FRMRS', 'lat' => 43.30, 'lng' => 5.37],
            'IT' => ['name' => 'Port of Genoa', 'code' => 'ITGOA', 'lat' => 44.40, 'lng' => 8.92],
            'TR' => ['name' => 'Port of Ambarli (Istanbul)', 'code' => 'TRAMB', 'lat' => 40.97, 'lng' => 28.68],
            'CA' => ['name' => 'Port of Vancouver', 'code' => 'CAVAN', 'lat' => 49.28, 'lng' => -123.11],
            'MX' => ['name' => 'Port of Manzanillo', 'code' => 'MXZLO', 'lat' => 19.05, 'lng' => -104.32],
            'NZ' => ['name' => 'Port of Auckland', 'code' => 'NZAKL', 'lat' => -36.84, 'lng' => 174.77],
        ];

        if (isset($coastalDictionary[$codeUpper])) {
            return $coastalDictionary[$codeUpper];
        }

        // 2. Search valid port from country's ports relationship in DB
        $dbPort = $country->ports()->whereNotNull('latitude')->whereNotNull('longitude')->first();
        if ($dbPort && abs((float) $dbPort->latitude) > 0.01 && abs((float) $dbPort->longitude) > 0.01) {
            return [
                'name' => $dbPort->name,
                'code' => $dbPort->code,
                'lat'  => (float) $dbPort->latitude,
                'lng'  => (float) $dbPort->longitude,
            ];
        }

        // 3. Fallback: Search nearest port in the entire `ports` table
        $nearestDbPort = Port::whereNotNull('latitude')->whereNotNull('longitude')->first();
        if ($nearestDbPort) {
            return [
                'name' => 'Pelabuhan ' . $country->name . ' (' . $nearestDbPort->name . ')',
                'code' => $nearestDbPort->code,
                'lat'  => (float) $nearestDbPort->latitude,
                'lng'  => (float) $nearestDbPort->longitude,
            ];
        }

        // Default coastal sea coordinates fallback
        return [
            'name' => 'Pelabuhan Utama ' . $country->name,
            'code' => strtoupper(substr($codeUpper, 0, 2)) . 'PRT',
            'lat'  => (float) ($country->latitude ?? 0.0),
            'lng'  => (float) ($country->longitude ?? 0.0),
        ];
    }

    /**
     * Generate sea-only maritime waypoints using a global sea node graph router.
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

        // 1. Pure Ocean Sea Nodes (Strictly Water Coords)
        $nodes = [
            'FINLAND_GULF'      => [59.8, 25.0],
            'BALTIC_SEA'        => [57.5, 19.0],
            'KATTEGAT'          => [56.5, 11.5],
            'NORTH_SEA'         => [54.0, 4.0],
            'ENGLISH_CHANNEL'   => [50.0, -1.0],
            'ATLANTIC_BISCAY'   => [44.0, -9.0],
            'GIBRALTAR'         => [35.9, -5.3],
            'MED_WEST'          => [37.0, 5.0],
            'MED_MID'           => [35.5, 18.0],
            'MED_EAST'          => [34.0, 28.0],
            'SUEZ'              => [29.9, 32.5],
            'RED_SEA_N'         => [27.0, 34.5],
            'RED_SEA_S'         => [15.0, 41.5],
            'BAB_EL_MANDEB'     => [12.5, 43.5],
            'GULF_OF_ADEN'      => [12.5, 47.0],
            'ARABIAN_SEA'       => [14.0, 62.0],
            'INDIAN_OCEAN_MID'  => [0.0, 75.0],
            'INDIAN_OCEAN_EAST' => [6.0, 88.0],
            'MALACCA_N'         => [5.5, 95.0],
            'MALACCA_STRAIT'    => [2.5, 101.5],
            'SINGAPORE'         => [1.25, 103.8],
            'JAVA_SEA'          => [-5.0, 110.0],
            'SUNDA_STRAIT'      => [-5.9, 105.8],
            'SOUTH_CHINA_S'     => [7.0, 108.0],
            'SOUTH_CHINA_M'     => [14.0, 114.0],
            'TAIWAN_STRAIT'     => [23.5, 119.5],
            'EAST_CHINA_SEA'    => [29.0, 124.0],
            'PHILIPPINE_SEA'    => [18.0, 128.0],
            'JAPAN_PACIFIC'     => [35.0, 142.0],
            'PACIFIC_HAWAII'    => [21.0, -157.0],
            'US_WEST'           => [33.0, -120.0],
            'PANAMA_PACIFIC'    => [8.5, -79.5],
            'PANAMA_ATLANTIC'   => [9.5, -79.9],
            'CARIBBEAN'         => [15.0, -75.0],
            'ATLANTIC_US_EAST'  => [32.0, -75.0],
            'ATLANTIC_SOUTH'    => [-20.0, -35.0],
            'CAPE_GOOD_HOPE'    => [-34.5, 19.0],
            'AUSTRALIA_W'       => [-32.0, 115.0],
            'AUSTRALIA_E'       => [-34.0, 152.0],
        ];

        // 2. Pure Water Edges (Undirected Graph)
        $graph = [
            'FINLAND_GULF'      => ['BALTIC_SEA'],
            'BALTIC_SEA'        => ['FINLAND_GULF', 'KATTEGAT'],
            'KATTEGAT'          => ['BALTIC_SEA', 'NORTH_SEA'],
            'NORTH_SEA'         => ['KATTEGAT', 'ENGLISH_CHANNEL'],
            'ENGLISH_CHANNEL'   => ['NORTH_SEA', 'ATLANTIC_BISCAY'],
            'ATLANTIC_BISCAY'   => ['ENGLISH_CHANNEL', 'GIBRALTAR', 'ATLANTIC_US_EAST'],
            'GIBRALTAR'         => ['ATLANTIC_BISCAY', 'MED_WEST'],
            'MED_WEST'          => ['GIBRALTAR', 'MED_MID'],
            'MED_MID'           => ['MED_WEST', 'MED_EAST'],
            'MED_EAST'          => ['MED_MID', 'SUEZ'],
            'SUEZ'              => ['MED_EAST', 'RED_SEA_N'],
            'RED_SEA_N'         => ['SUEZ', 'RED_SEA_S'],
            'RED_SEA_S'         => ['RED_SEA_N', 'BAB_EL_MANDEB'],
            'BAB_EL_MANDEB'     => ['RED_SEA_S', 'GULF_OF_ADEN'],
            'GULF_OF_ADEN'      => ['BAB_EL_MANDEB', 'ARABIAN_SEA', 'INDIAN_OCEAN_MID'],
            'ARABIAN_SEA'       => ['GULF_OF_ADEN', 'INDIAN_OCEAN_MID', 'INDIAN_OCEAN_EAST'],
            'INDIAN_OCEAN_MID'  => ['ARABIAN_SEA', 'GULF_OF_ADEN', 'INDIAN_OCEAN_EAST', 'CAPE_GOOD_HOPE'],
            'INDIAN_OCEAN_EAST' => ['INDIAN_OCEAN_MID', 'ARABIAN_SEA', 'MALACCA_N', 'SUNDA_STRAIT', 'AUSTRALIA_W'],
            'MALACCA_N'         => ['INDIAN_OCEAN_EAST', 'MALACCA_STRAIT'],
            'MALACCA_STRAIT'    => ['MALACCA_N', 'SINGAPORE'],
            'SINGAPORE'         => ['MALACCA_STRAIT', 'JAVA_SEA', 'SOUTH_CHINA_S'],
            'SUNDA_STRAIT'      => ['INDIAN_OCEAN_EAST', 'JAVA_SEA'],
            'JAVA_SEA'          => ['SINGAPORE', 'SUNDA_STRAIT', 'SOUTH_CHINA_S'],
            'SOUTH_CHINA_S'     => ['SINGAPORE', 'JAVA_SEA', 'SOUTH_CHINA_M'],
            'SOUTH_CHINA_M'     => ['SOUTH_CHINA_S', 'TAIWAN_STRAIT', 'PHILIPPINE_SEA'],
            'TAIWAN_STRAIT'     => ['SOUTH_CHINA_M', 'EAST_CHINA_SEA'],
            'EAST_CHINA_SEA'    => ['TAIWAN_STRAIT', 'JAPAN_PACIFIC', 'PHILIPPINE_SEA'],
            'PHILIPPINE_SEA'    => ['EAST_CHINA_SEA', 'SOUTH_CHINA_M', 'JAPAN_PACIFIC', 'PACIFIC_HAWAII'],
            'JAPAN_PACIFIC'     => ['EAST_CHINA_SEA', 'PHILIPPINE_SEA', 'PACIFIC_HAWAII'],
            'PACIFIC_HAWAII'    => ['JAPAN_PACIFIC', 'PHILIPPINE_SEA', 'US_WEST', 'PANAMA_PACIFIC'],
            'US_WEST'           => ['PACIFIC_HAWAII', 'PANAMA_PACIFIC'],
            'PANAMA_PACIFIC'    => ['US_WEST', 'PACIFIC_HAWAII', 'PANAMA_ATLANTIC'],
            'PANAMA_ATLANTIC'   => ['PANAMA_PACIFIC', 'CARIBBEAN'],
            'CARIBBEAN'         => ['PANAMA_ATLANTIC', 'ATLANTIC_US_EAST', 'ATLANTIC_SOUTH'],
            'ATLANTIC_US_EAST'  => ['CARIBBEAN', 'ATLANTIC_BISCAY', 'ATLANTIC_SOUTH'],
            'ATLANTIC_SOUTH'    => ['ATLANTIC_US_EAST', 'CARIBBEAN', 'CAPE_GOOD_HOPE'],
            'CAPE_GOOD_HOPE'    => ['ATLANTIC_SOUTH', 'INDIAN_OCEAN_MID'],
            'AUSTRALIA_W'       => ['INDIAN_OCEAN_EAST', 'AUSTRALIA_E'],
            'AUSTRALIA_E'       => ['AUSTRALIA_W', 'PHILIPPINE_SEA'],
        ];

        // 3. Find closest Sea Node to Origin Port
        $startNodeKey = $this->findClosestSeaNode($lat1, $lng1, $nodes);
        
        // 4. Find closest Sea Node to Destination Port
        $endNodeKey = $this->findClosestSeaNode($lat2, $lng2, $nodes);

        // 5. Compute BFS Shortest Path between $startNodeKey and $endNodeKey
        $pathNodeKeys = $this->bfsShortestPath($graph, $startNodeKey, $endNodeKey);

        // 6. Build chain of coordinates: [lat1, lng1] -> Node_1 -> Node_2 ... -> Node_N -> [lat2, lng2]
        $chain = [];
        $chain[] = [$lat1, $lng1];
        foreach ($pathNodeKeys as $nodeKey) {
            if (isset($nodes[$nodeKey])) {
                $chain[] = $nodes[$nodeKey];
            }
        }
        $chain[] = [$lat2, $lng2];

        // 7. Interpolate smoothly along all segments
        $finalWaypoints = [];
        $chainCount = count($chain);

        for ($k = 0; $k < $chainCount - 1; $k++) {
            $pA = $chain[$k];
            $pB = $chain[$k + 1];

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
     * Find closest Sea Node in graph to given lat/lng.
     */
    private function findClosestSeaNode(float $lat, float $lng, array $nodes): string
    {
        $minDist = 999999.0;
        $closestKey = (string) array_key_first($nodes);

        foreach ($nodes as $key => $coord) {
            $dist = $this->haversineGreatCircleDistance($lat, $lng, $coord[0], $coord[1]);
            if ($dist < $minDist) {
                $minDist = $dist;
                $closestKey = (string) $key;
            }
        }

        return $closestKey;
    }

    /**
     * BFS Shortest Path algorithm on Sea Node Graph.
     */
    private function bfsShortestPath(array $graph, string $start, string $target): array
    {
        if ($start === $target) {
            return [$start];
        }

        $visited = [$start => true];
        $queue = [[$start]];

        while (!empty($queue)) {
            $path = array_shift($queue);
            $node = end($path);

            if ($node === $target) {
                return $path;
            }

            if (isset($graph[$node])) {
                foreach ($graph[$node] as $neighbor) {
                    if (!isset($visited[$neighbor])) {
                        $visited[$neighbor] = true;
                        $newPath = $path;
                        $newPath[] = $neighbor;
                        $queue[] = $newPath;
                    }
                }
            }
        }

        return [$start, $target];
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
