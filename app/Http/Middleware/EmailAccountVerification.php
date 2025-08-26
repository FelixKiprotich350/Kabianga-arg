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
        // Skip verification check for verification routes
        if ($request->routeIs('pages.account.verifyemail', 'verification.verify', 'verification.resend')) {
            return $next($request);
        }

        // Check if the user is authenticated but not email verified
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('pages.account.verifyemail');
        }

        return $next($request);
    }
}

