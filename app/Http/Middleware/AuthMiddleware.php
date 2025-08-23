<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        // Check authentication
        if (!Auth::check()) {
            return $request->expectsJson() 
                ? response()->json(['message' => 'Unauthenticated'], 401)
                : redirect()->route('pages.login');
        }

        $user = Auth::user();
        
        // Ensure user exists and has required properties
        if (!$user) {
            Auth::logout();
            return redirect()->route('pages.login');
        }

        // Skip email verification check for now to avoid redirect loops
        // if (method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
        //     return $request->expectsJson()
        //         ? response()->json(['message' => 'Email not verified'], 403)
        //         : redirect()->route('pages.account.verifyemail');
        // }

        // Check if user is active (skip if property doesn't exist)
        if (property_exists($user, 'isactive') && isset($user->isactive) && !$user->isactive) {
            Auth::logout();
            return $request->expectsJson()
                ? response()->json(['message' => 'Account disabled'], 403)
                : redirect()->route('pages.login')->with('error', 'Account disabled');
        }

        // Check permissions if specified
        if (!empty($permissions)) {
            foreach ($permissions as $permission) {
                if (method_exists($user, 'haspermission') && !$user->haspermission($permission)) {
                    return $request->expectsJson()
                        ? response()->json(['message' => 'Insufficient permissions'], 403)
                        : redirect()->route('pages.unauthorized');
                }
            }
        }

        return $next($request);
    }
}