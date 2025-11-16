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
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        
        if (!$user || (isset($user->isactive) && !$user->isactive)) {
            Auth::logout();
            return response()->json(['message' => 'Account inactive'], 401);
        }

        // Check access requirements
        if (!empty($requirements)) {
            if (!AccessControlService::hasAccess($requirements)) {
                return response()->json(['message' => 'Access denied'], 403);
            }
        }

        return $next($request);
    }
}