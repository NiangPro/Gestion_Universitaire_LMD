<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Historique;
use Illuminate\Support\Facades\Auth;

class LogLoginAttempts
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request)->then(function ($response) use ($request) {
            if (Auth::check()) {
                Historique::create([
                    'type' => 'connexion',
                    'description' => 'Connexion réussie',
                    'ip' => $request->ip(),
                    'navigateur' => $request->userAgent(),
                    'user_id' => Auth::id(),
                    'campus_id' => Auth::user()->campus_id,
                ]);
            } elseif ($request->is('login') && $request->isMethod('post')) {
                Historique::create([
                    'type' => 'tentative_connexion',
                    'description' => 'Tentative de connexion échouée pour ' . $request->input('email'),
                    'ip' => $request->ip(),
                    'navigateur' => $request->userAgent(),
                    'campus_id' => null,
                ]);
            }
        });
    }
}
