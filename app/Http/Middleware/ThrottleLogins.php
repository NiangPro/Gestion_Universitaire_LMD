<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThrottleLogins
{
    protected $rateLimiter;

    public function __construct(RateLimiter $rateLimiter)
    {
        $this->rateLimiter = $rateLimiter;
    }

    public function handle(Request $request, Closure $next)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->rateLimiter->tooManyAttempts($key, 5)) { // 5 tentatives maximum
            $seconds = $this->rateLimiter->availableIn($key);
            
            return response()->json([
                'error' => 'Too many login attempts.',
                'seconds_remaining' => $seconds,
                'message' => "Trop de tentatives de connexion. Veuillez réessayer dans {$seconds} secondes."
            ], 429);
        }

        $this->rateLimiter->hit($key, 300); // Bloquer pendant 5 minutes (300 secondes)

        $response = $next($request);

        if ($response->getStatusCode() === 200) {
            $this->rateLimiter->clear($key);
        }

        return $response;
    }

    protected function resolveRequestSignature($request)
    {
        return Str::lower($request->input('login')|'').'|'.$request->ip();
    }
}
