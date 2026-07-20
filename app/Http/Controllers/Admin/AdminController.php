<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Port;
use App\Models\NewsArticle;
use App\Models\Country;

/**
 * ═══════════════════════════════════════════════════════════
 * ADMIN CONTROLLER – Page Routing with Data
 * app/Http/Controllers/Admin/AdminController.php
 * ═══════════════════════════════════════════════════════════
 */
class AdminController extends Controller
{
    /**
     * Admin Dashboard Overview.
     * Route: GET /admin
     */
    public function dashboard(): View
    {
        $usersCount = User::count();
        $portsCount = Port::count();
        $articlesCount = NewsArticle::count();
        $countriesCount = Country::count();
        $watchlistsCount = \App\Models\Watchlist::count();
        $riskRecordsCount = \App\Models\RiskScore::count();
        $recentUsers = User::latest()->limit(5)->get();

        $apiLogsCount = \App\Models\ApiLog::count();
        $avgRiskScore = \App\Models\RiskScore::avg('final_risk_score') ?? 0;
        $todayUsersCount = User::whereDate('created_at', today())->count();

        return view('pages.admin.dashboard.index', compact(
            'usersCount', 'portsCount', 'articlesCount', 'countriesCount', 'watchlistsCount', 'riskRecordsCount', 'recentUsers',
            'apiLogsCount', 'avgRiskScore', 'todayUsersCount'
        ));
    }

    /**
     * User Management.
     * Route: GET /admin/users
     */
    public function users(): View
    {
        $users = User::latest()->get();
        return view('pages.admin.users.index', compact('users'));
    }

    /**
     * Port Dataset Management.
     * Route: GET /admin/ports
     */
    public function ports(): View
    {
        $ports = Port::with('country')->latest()->get();
        return view('pages.admin.ports.index', compact('ports'));
    }

    /**
     * Article Management.
     * Route: GET /admin/articles
     */
    public function articles(): View
    {
        $articles = NewsArticle::latest()->get();
        return view('pages.admin.articles.index', compact('articles'));
    }

    /**
     * Article Create Page.
     * Route: GET /admin/articles/create
     */
    public function articleCreate(): View
    {
        $countries = Country::all();
        return view('admin.articles.create', compact('countries'));
    }

    /**
     * Article Edit Page.
     * Route: GET /admin/articles/{id}/edit
     */
    public function articleEdit(int $id): View
    {
        $article = NewsArticle::find($id);
        $countries = Country::all();
        return view('admin.articles.edit', compact('article', 'id', 'countries'));
    }

    /**
     * Article Detail Page.
     * Route: GET /admin/articles/{id}
     */
    public function articleDetail(int $id): View
    {
        $article = NewsArticle::find($id);
        return view('admin.articles.detail', compact('article', 'id'));
    }
}
