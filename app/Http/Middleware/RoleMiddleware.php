<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('pages.login');
        }

        $user = Auth::user();
        
        if ($user->isadmin) {
            return $next($request);
        }

        if (!in_array($user->role, $roles)) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Access denied'], 403)
                : redirect()->route('pages.unauthorized');
        }

        return $next($request);
    }
}