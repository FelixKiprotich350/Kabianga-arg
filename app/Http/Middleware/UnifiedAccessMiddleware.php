<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AccessControlService;

class UnifiedAccessMiddleware
{
    public function handle(Request $request, Closure $next, ...$requirements)
    {
        if (!Auth::check()) {
            return $request->expectsJson() 
                ? response()->json(['message' => 'Unauthenticated'], 401)
                : redirect()->route('pages.login');
        }

        $user = Auth::user();
        
        if (!$user || (isset($user->isactive) && !$user->isactive)) {
            Auth::logout();
            return redirect()->route('pages.login');
        }

        // Check access requirements
        if (!empty($requirements)) {
            if (!AccessControlService::hasAccess($requirements)) {
                return $request->expectsJson()
                    ? response()->json(['message' => 'Access denied'], 403)
                    : redirect()->route('pages.unauthorized');
            }
        }

        return $next($request);
    }
}