<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureKasir
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check() && in_array(Auth::user()->role, ['kasir', 'supervisor'])) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}