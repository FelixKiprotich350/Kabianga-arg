<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EmailAccountVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip verification check for API verification routes
        if ($request->is('api/*/auth/verify*') || $request->is('api/*/auth/resend*')) {
            return $next($request);
        }

        // Check if the user is authenticated but not email verified
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email verification required',
                'error' => 'email_not_verified'
            ], 403);
        }

        return $next($request);
    }
}

