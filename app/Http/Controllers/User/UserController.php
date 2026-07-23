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
        $countriesCount = Country::count();
        $portsCount     = Port::count();
        $articlesCount  = NewsArticle::count();
        $avgRiskScore   = RiskScore::avg('final_risk_score') ?? 0;

        $topHighRisks = Country::with(['riskScore'])
            ->whereHas('riskScore')
            ->get()
            ->sortByDesc(fn($c) => $c->riskScore?->final_risk_score ?? 0)
            ->take(5);

        $topLowRisks = Country::with(['riskScore'])
            ->whereHas('riskScore')
            ->get()
            ->sortBy(fn($c) => $c->riskScore?->final_risk_score ?? 0)
            ->take(5);

        $latestNews = NewsArticle::latest()->take(4)->get();
        $portsList  = Port::with(['country.riskScore'])->take(5)->get();
        $watchlistsCount = Watchlist::where('user_id', Auth::id())->count();

        return view('pages.user.dashboard.index', compact(
            'countriesCount',
            'portsCount',
            'articlesCount',
            'avgRiskScore',
            'topHighRisks',
            'topLowRisks',
            'latestNews',
            'portsList',
            'watchlistsCount'
        ));
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
        $countries = Country::with(['currency', 'region', 'riskScore'])->get();
        $currencies = Currency::all();
        return view('pages.user.currency.index', compact('countries', 'currencies'));
    }


    /**
     * News Module.
     * Route: GET /dashboard/news
     */
    public function news(): View
    {
        $countries = Country::with(['region', 'currency'])->get();
        $articles = NewsArticle::with('country')->latest()->get();
        return view('pages.user.news.index', compact('countries', 'articles'));
    }


    /**
     * Risk Analysis Module.
     * Route: GET /dashboard/risk
     */
    public function risk(): View
    {
        $countries = Country::with(['riskScore.classification', 'region'])->get();
        $riskScores = RiskScore::with(['country.region', 'classification'])->orderBy('final_risk_score', 'desc')->get();
        return view('pages.user.risk.index', compact('countries', 'riskScores'));
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
        $countries = Country::with(['riskScore.classification', 'region', 'currency'])->get();
        return view('pages.user.favorite.index', compact('countries'));
    }


    /**
     * Visualization Module.
     * Route: GET /dashboard/visualization
     */
    public function visualization(): View
    {
        $countries = Country::with(['riskScore.classification', 'region', 'currency'])->get();
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
     * Ports Module – World Port Index Dataset.
     * Route: GET /dashboard/ports
     */
    public function ports(): View
    {
        $ports     = Port::with(['country.region', 'country.riskScore.classification'])->get();
        $countries = Country::with(['region'])->orderBy('name')->get();

        // Aggregate stats
        $totalPorts  = $ports->count();
        $largePorts  = $ports->where('size', 'Large')->count();
        $mediumPorts = $ports->where('size', 'Medium')->count();
        $smallPorts  = $ports->where('size', 'Small')->count();

        // Continent breakdown
        $byContinent = $ports->groupBy(fn ($p) => $p->country?->region?->name ?? 'Other')
            ->map->count();

        $portJsonData = $ports->map(function ($p) {
            return [
                'id'          => $p->id,
                'code'        => $p->code,
                'name'        => $p->name,
                'country'     => $p->country?->name ?? '—',
                'countryCode' => strtolower($p->country?->code ?? 'un'),
                'continent'   => $p->country?->region?->name ?? '—',
                'lat'         => (float) $p->latitude,
                'lng'         => (float) $p->longitude,
                'size'        => $p->size ?? '—',
                'type'        => $p->type ?? '—',
                'harbor'      => $p->harbor_type ?? '—',
            ];
        })->values();

        return view('pages.user.ports.index', compact(
            'ports', 'countries',
            'totalPorts', 'largePorts', 'mediumPorts', 'smallPorts',
            'byContinent', 'portJsonData'
        ));
    }

    /**
     * Package Shipping & Port Routing Simulation Module.
     * Route: GET /dashboard/simulation
     */
    public function simulation(): View
    {
        $countries = Country::with(['ports', 'riskScore'])->orderBy('name')->get();
        return view('pages.user.simulation.index', compact('countries'));
    }
}
