<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * ═══════════════════════════════════════════════════════════
 * ADMIN MIDDLEWARE – Authentication Flow
 * app/Http/Middleware/AdminMiddleware.php
 *
 * Protects admin routes:
 *   - Not authenticated → redirect to login
 *   - Authenticated but not admin → redirect to user dashboard
 * ═══════════════════════════════════════════════════════════
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak. Anda bukan administrator.');
        }

        return $next($request);
    }
}
