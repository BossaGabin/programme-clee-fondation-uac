<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsCoach
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'coach') {
            abort(403, 'Accès refusé.');
        }
        return $next($request);
    }
}