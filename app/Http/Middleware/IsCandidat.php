<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsCandidat
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'candidat') {
            abort(403, 'Accès refusé.');
        }
        return $next($request);
    }
}