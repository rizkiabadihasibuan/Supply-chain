<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.index');
    }

    public function countries(): View
    {
        return view('countries.index');
    }

    public function weather(): View
    {
        return view('weather.index');
    }

    public function currency(): View
    {
        return view('currency.index');
    }

    public function ports(): View
    {
        return view('ports.index');
    }

    public function news(): View
    {
        return view('news.index');
    }

    public function risk(): View
    {
        return view('risk.index');
    }

    public function comparison(): View
    {
        return view('comparison.index');
    }

    public function watchlist(): View
    {
        return view('watchlist.index');
    }

    public function admin(): View
    {
        return view('admin.index');
    }
}
