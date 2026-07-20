<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Models\RiskScore;

class RiskHistoryController extends BaseApiController
{
    /**
     * index method — Get risk history log or recent risk scores
     * GET /api/v1/risk/history
     */
    public function index(Request $request)
    {
        try {
            $countryId = (int) $request->query('country_id', 0);

            $query = RiskScore::with('country');

            if ($countryId > 0) {
                $query->where('country_id', $countryId);
            }

            $riskScores = $query->orderBy('updated_at', 'desc')->limit(10)->get();

            $result = $riskScores->map(function ($item) {
                return [
                    'id' => $item->id,
                    'country_name' => $item->country?->name ?? 'Global',
                    'country_code' => $item->country?->code ?? 'GL',
                    'score' => (float) $item->final_risk_score,
                    'level' => $item->risk_level,
                    'recorded_at' => $item->updated_at?->toIso8601String() ?? now()->toIso8601String(),
                    'time_ago' => $item->updated_at ? $item->updated_at->diffForHumans() : 'baru saja',
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Risk history retrieved successfully',
                'data' => $result,
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve risk history', [$e->getMessage()], 500);
        }
    }

    /**
     * show method
     */
    public function show($id)
    {
        try {
            return $this->sendSuccess('Method show executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute show', [$e->getMessage()], 500);
        }
    }
}