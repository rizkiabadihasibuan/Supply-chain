<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Models\Watchlist;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends BaseApiController
{
    /**
     * index method — Get watchlists for authenticated user or default list
     * GET /api/v1/watchlists
     */
    public function index(Request $request)
    {
        try {
            $userId = Auth::id() ?? 1; // Fallback to user ID 1 if unauthenticated

            $watchlists = Watchlist::with(['countries.riskScore', 'countries.region'])
                ->where('user_id', $userId)
                ->get();

            // If user has no watchlist, create default
            if ($watchlists->isEmpty()) {
                $defaultWatchlist = Watchlist::create([
                    'user_id' => $userId,
                    'name' => 'Default Monitoring List',
                    'description' => 'Daftar negara pantauan utama risiko rantai pasok.',
                    'status' => 'active',
                ]);
                $watchlists = collect([$defaultWatchlist->load(['countries.riskScore', 'countries.region'])]);
            }

            $result = $watchlists->map(function ($wl) {
                return [
                    'id' => $wl->id,
                    'name' => $wl->name,
                    'description' => $wl->description,
                    'status' => $wl->status,
                    'total_countries' => $wl->countries->count(),
                    'countries' => $wl->countries->map(function ($country) {
                        return [
                            'id' => $country->id,
                            'name' => $country->name,
                            'code' => $country->code,
                            'flag_url' => $country->flag_url,
                            'region' => $country->region?->name,
                            'risk_score' => (float) ($country->riskScore?->final_risk_score ?? 0.0),
                            'risk_level' => $country->riskScore?->risk_level ?? 'Not Calculated',
                        ];
                    }),
                    'created_at' => $wl->created_at?->toIso8601String(),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Watchlists retrieved successfully',
                'data' => $result,
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve watchlists', [$e->getMessage()], 500);
        }
    }

    /**
     * store method — Add country to watchlist or create new watchlist
     * POST /api/v1/watchlists
     */
    public function store(Request $request)
    {
        try {
            $userId = Auth::id() ?? 1;

            $request->validate([
                'country_id' => 'required_without:name|integer|exists:countries,id',
                'name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ]);

            // Case A: Create new watchlist
            if ($request->has('name') && !$request->has('country_id')) {
                $watchlist = Watchlist::create([
                    'user_id' => $userId,
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'status' => 'active',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Watchlist created successfully',
                    'data' => $watchlist,
                ], 201);
            }

            // Case B: Add country to watchlist
            $watchlist = Watchlist::firstOrCreate(
                ['user_id' => $userId, 'status' => 'active'],
                ['name' => 'Default Watchlist', 'description' => 'Pantauan Utama']
            );

            $countryId = $request->input('country_id');
            $watchlist->countries()->syncWithoutDetaching([$countryId]);

            $country = Country::with('riskScore')->find($countryId);

            return response()->json([
                'success' => true,
                'message' => "Negara {$country->name} berhasil ditambahkan ke Watchlist",
                'data' => [
                    'watchlist_id' => $watchlist->id,
                    'country' => [
                        'id' => $country->id,
                        'name' => $country->name,
                        'code' => $country->code,
                        'risk_score' => (float) ($country->riskScore?->final_risk_score ?? 0.0),
                    ],
                ],
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to update watchlist', [$e->getMessage()], 500);
        }
    }

    /**
     * destroy method — Remove country from watchlist or delete watchlist
     * DELETE /api/v1/watchlists/{id}?country_id=5
     */
    public function destroy(Request $request, $id)
    {
        try {
            $userId = Auth::id() ?? 1;

            $watchlist = Watchlist::where('id', $id)->where('user_id', $userId)->first();

            if (!$watchlist) {
                // Fallback try find any watchlist
                $watchlist = Watchlist::find($id);
            }

            if (!$watchlist) {
                return $this->sendError('Watchlist not found', [], 404);
            }

            // If country_id provided in query, remove only that country
            if ($request->has('country_id')) {
                $countryId = (int) $request->query('country_id');
                $watchlist->countries()->detach($countryId);

                return response()->json([
                    'success' => true,
                    'message' => 'Negara berhasil dihapus dari Watchlist',
                ], 200);
            }

            // Otherwise delete entire watchlist
            $watchlist->countries()->detach();
            $watchlist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Watchlist deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to delete watchlist item', [$e->getMessage()], 500);
        }
    }
}