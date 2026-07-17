<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isOwner()) {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Halaman ini hanya untuk Owner.');
    }
}
