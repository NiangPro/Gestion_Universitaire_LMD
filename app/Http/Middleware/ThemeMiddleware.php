<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ThemeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('darkMode')) {
            // Si pas de préférence en session, utiliser la valeur par défaut
            session()->put('darkMode', false);
        }

        return $next($request);
    }
} 