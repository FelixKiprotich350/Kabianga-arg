<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        
        if ($user->isadmin) {
            return $next($request);
        }

        // Check if user has any of the required permissions
        $hasPermission = false;
        foreach ($permissions as $permission) {
            if ($user->haspermission($permission)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return $next($request);
    }
}