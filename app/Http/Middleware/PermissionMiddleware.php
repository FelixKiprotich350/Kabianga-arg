<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = Auth::guard('api')->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Check if user is active
        if (!$user->isactive) {
            return response()->json(['message' => 'Account disabled'], 403);
        }

        // Check permissions if specified
        if (!empty($permissions)) {
            foreach ($permissions as $permission) {
                if (!$user->haspermission($permission)) {
                    return response()->json(['message' => 'Insufficient permissions'], 403);
                }
            }
        }

        return $next($request);
    }
}