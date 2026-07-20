<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Country;
use App\Models\Port;
use App\Models\NewsArticle;
use App\Models\Currency;
use App\Models\RiskScore;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;

/**
 * ═══════════════════════════════════════════════════════════
 * USER CONTROLLER – Authentication & Page Routing
 * app/Http/Controllers/User/UserController.php
 * ═══════════════════════════════════════════════════════════
 */
class UserController extends Controller
{
    /**
     * User Dashboard.
     * Route: GET /dashboard
     */
    public function dashboard(): View
    {
        return view('pages.user.dashboard.index');
    }

    /**
     * Countries Module.
     * Route: GET /dashboard/countries
     */
    public function countries(): View
    {
        $countries = Country::with(['region', 'currency', 'riskScore'])->get();
        return view('pages.user.countries.index', compact('countries'));
    }

    /**
     * Weather Module.
     * Route: GET /dashboard/weather
     */
    public function weather(): View
    {
        $countries = Country::with(['riskScore'])->get();
        $ports = Port::with('country')->get();
        return view('pages.user.weather.index', compact('countries', 'ports'));
    }

    /**
     * Currency Exchange Module.
     * Route: GET /dashboard/currency
     */
    public function currency(): View
    {
        $currencies = Currency::all();
        return view('pages.user.currency.index', compact('currencies'));
    }

    /**
     * News Module.
     * Route: GET /dashboard/news
     */
    public function news(): View
    {
        $articles = NewsArticle::latest()->get();
        return view('pages.user.news.index', compact('articles'));
    }

    /**
     * Risk Analysis Module.
     * Route: GET /dashboard/risk
     */
    public function risk(): View
    {
        $riskScores = RiskScore::with(['country.region', 'classification'])->orderBy('final_risk_score', 'desc')->get();
        return view('pages.user.risk.index', compact('riskScores'));
    }

    /**
     * Country Comparison Module.
     * Route: GET /dashboard/comparison
     */
    public function comparison(): View
    {
        $countries = Country::orderBy('name')->get();
        return view('pages.user.comparison.index', compact('countries'));
    }

    /**
     * Favorite / Watchlist Module.
     * Route: GET /dashboard/favorite
     */
    public function favorite(): View
    {
        $userId = Auth::id() ?? 1;
        $watchlists = Watchlist::with(['countries.riskScore', 'countries.region'])
            ->where('user_id', $userId)
            ->get();

        return view('pages.user.favorite.index', compact('watchlists'));
    }

    /**
     * Visualization Module.
     * Route: GET /dashboard/visualization
     */
    public function visualization(): View
    {
        $countries = Country::with('riskScore')->get();
        return view('dashboard.visualization.index', compact('countries'));
    }

    /**
     * Profile Module.
     * Route: GET /dashboard/profile
     */
    public function profile(): View
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Ports Module.
     * Route: GET /dashboard/ports
     */
    public function ports(): View
    {
        $ports = Port::with('country.riskScore')->get();
        return view('ports.index', compact('ports'));
    }
}
