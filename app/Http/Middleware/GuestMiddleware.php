<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * ═══════════════════════════════════════════════════════════
 * GUEST MIDDLEWARE – Authentication Flow
 * app/Http/Middleware/GuestMiddleware.php
 *
 * Redirects authenticated users away from guest-only pages
 * (login, register, landing) to their respective dashboards.
 * ═══════════════════════════════════════════════════════════
 */
class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
