<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->two_factor_secret && 
            !$request->session()->has('two_factor_confirmed')) {
            return redirect()->route('two-factor.login');
        }

        return $next($request);
    }
}
