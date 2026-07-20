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
     * Store new User.
     * Route: POST /admin/users
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'nullable|string|in:user,admin',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role'     => $validated['role'] ?? 'user',
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan ke database.');
    }

    /**
     * Delete User.
     * Route: DELETE /admin/users/{id}
     */
    public function destroyUser(int $id)
    {
        $user = User::findOrFail($id);
        if ($user->id !== auth()->id()) {
            $user->delete();
        }

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

    /**
     * Store new Port.
     * Route: POST /admin/ports
     */
    public function storePort(Request $request)
    {
        $validated = $request->validate([
            'code'       => 'required|string|unique:ports,code|max:10',
            'name'       => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'size'       => 'nullable|string',
            'type'       => 'nullable|string',
        ]);

        Port::create([
            'code'       => strtoupper($validated['code']),
            'name'       => $validated['name'],
            'country_id' => $validated['country_id'],
            'latitude'   => $validated['latitude'],
            'longitude'  => $validated['longitude'],
            'size'       => $validated['size'] ?? 'Medium',
            'type'       => $validated['type'] ?? 'Seaport',
        ]);

        return redirect()->back()->with('success', 'Pelabuhan baru berhasil ditambahkan.');
    }

    /**
     * Delete Port.
     * Route: DELETE /admin/ports/{id}
     */
    public function destroyPort(int $id)
    {
        $port = Port::findOrFail($id);
        $port->delete();

        return redirect()->back()->with('success', 'Pelabuhan berhasil dihapus.');
    }

    /**
     * Store new Article.
     * Route: POST /admin/articles
     */
    public function storeArticle(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'nullable|string',
            'description' => 'nullable|string',
            'category'    => 'nullable|string',
            'country_id'  => 'nullable|exists:countries,id',
        ]);

        NewsArticle::create([
            'title'        => $validated['title'],
            'content'      => $validated['content'] ?? $validated['title'],
            'description'  => $validated['description'] ?? '',
            'category'     => $validated['category'] ?? 'Supply Chain',
            'country_id'   => $validated['country_id'] ?? null,
            'source'       => 'Admin',
            'published_at' => now(),
        ]);

        return redirect()->route('admin.articles')->with('success', 'Artikel berhasil diterbitkan.');
    }

    /**
     * Delete Article.
     * Route: DELETE /admin/articles/{id}
     */
    public function destroyArticle(int $id)
    {
        $article = NewsArticle::findOrFail($id);
        $article->delete();

        return redirect()->back()->with('success', 'Artikel berhasil dihapus.');
    }
}
