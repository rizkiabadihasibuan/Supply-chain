<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportExportController extends Controller
{
    /**
     * GET /dashboard/export/country/{id}
     * Generate an executive printable risk report for a country.
     */
    public function exportCountryReport(int $id): View
    {
        $country = Country::with(['region', 'currency', 'riskScore', 'riskAlerts'])->findOrFail($id);

        $riskScore = $country->riskScore;
        $finalScore = $riskScore?->final_risk_score ?? 20.0;
        $riskLevel = ucfirst(strtolower($riskScore?->risk_level ?? 'low'));

        $badgeClass = match (strtolower($riskLevel)) {
            'critical', 'high' => 'danger',
            'medium' => 'warning',
            default => 'success',
        };

        return view('reports.executive_country_report', compact(
            'country',
            'riskScore',
            'finalScore',
            'riskLevel',
            'badgeClass'
        ));
    }
}
