<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * ═══════════════════════════════════════════════════════════
 * ADMIN CONTROLLER – Milestone 3.16A
 * app/Http/Controllers/Admin/AdminController.php
 *
 * Handles all Admin panel page routing.
 * Uses layouts.admin.app (pages/admin/*).
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
        return view('pages.admin.dashboard.index');
    }

    /**
     * User Management.
     * Route: GET /admin/users
     */
    public function users(): View
    {
        return view('pages.admin.users.index');
    }

    /**
     * Port Dataset Management.
     * Route: GET /admin/ports
     */
    public function ports(): View
    {
        return view('pages.admin.ports.index');
    }

    /**
     * Article Management.
     * Route: GET /admin/articles
     */
    public function articles(): View
    {
        return view('pages.admin.articles.index');
    }

    /**
     * Article Create Page.
     * Route: GET /admin/articles/create
     */
    public function articleCreate(): View
    {
        return view('admin.articles.create');
    }

    /**
     * Article Edit Page.
     * Route: GET /admin/articles/{id}/edit
     */
    public function articleEdit(int $id): View
    {
        return view('admin.articles.edit', compact('id'));
    }

    /**
     * Article Detail Page.
     * Route: GET /admin/articles/{id}
     */
    public function articleDetail(int $id): View
    {
        return view('admin.articles.detail', compact('id'));
    }
}
