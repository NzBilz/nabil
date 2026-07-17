<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KasirMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isKasir()) {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Halaman ini hanya untuk Kasir.');
    }
}
