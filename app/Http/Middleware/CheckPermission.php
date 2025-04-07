<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $module, $action)
    {
        if (!auth()->user()->hasPermission($module, $action)) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Non autorisé'], 403);
            }
            
            session()->flash('error', 'Vous n\'avez pas la permission d\'effectuer cette action.');
            return redirect()->back();
        }

        return $next($request);
    }
} 