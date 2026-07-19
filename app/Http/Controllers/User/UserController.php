<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * ═══════════════════════════════════════════════════════════
 * USER CONTROLLER – Authentication Flow
 * app/Http/Controllers/User/UserController.php
 *
 * Handles all User panel page routing.
 * Uses layouts.user.app (pages/user/*).
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
        return view('pages.user.countries.index');
    }

    /**
     * Weather Module.
     * Route: GET /dashboard/weather
     */
    public function weather(): View
    {
        return view('pages.user.weather.index');
    }

    /**
     * Currency Exchange Module.
     * Route: GET /dashboard/currency
     */
    public function currency(): View
    {
        return view('pages.user.currency.index');
    }

    /**
     * News Module.
     * Route: GET /dashboard/news
     */
    public function news(): View
    {
        return view('pages.user.news.index');
    }

    /**
     * Risk Analysis Module.
     * Route: GET /dashboard/risk
     */
    public function risk(): View
    {
        return view('pages.user.risk.index');
    }

    /**
     * Country Comparison Module.
     * Route: GET /dashboard/comparison
     */
    public function comparison(): View
    {
        return view('pages.user.comparison.index');
    }

    /**
     * Favorite / Watchlist Module.
     * Route: GET /dashboard/favorite
     */
    public function favorite(): View
    {
        return view('pages.user.favorite.index');
    }

    /**
     * Visualization Module.
     * Route: GET /dashboard/visualization
     */
    public function visualization(): View
    {
        return view('dashboard.visualization.index');
    }

    /**
     * Profile Module.
     * Route: GET /dashboard/profile
     */
    public function profile(): View
    {
        return view('profile.index');
    }

    /**
     * Ports Module.
     * Route: GET /dashboard/ports
     */
    public function ports(): View
    {
        return view('ports.index');
    }
}
