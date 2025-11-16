<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        try {
            // Try to authenticate user via JWT
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['message' => 'Token invalid or expired'], 401);
        }
        
        // Ensure user exists and has required properties
        if (!$user) {
            return response()->json(['message' => 'User not found'], 401);
        }

        // Check if user is active
        if (property_exists($user, 'isactive') && isset($user->isactive) && !$user->isactive) {
            return response()->json(['message' => 'Account disabled'], 403);
        }

        // Check permissions if specified
        if (!empty($permissions)) {
            foreach ($permissions as $permission) {
                if (method_exists($user, 'haspermission') && !$user->haspermission($permission)) {
                    return response()->json(['message' => 'Insufficient permissions'], 403);
                }
            }
        }

        return $next($request);
    }
}