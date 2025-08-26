<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    //

    public function showLoginForm()
    {
        if (Auth::check()) {
            // return response("authorised");
            return redirect()->route('pages.dashboard');
        } else {
            return view('pages.auth.login');

        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $rememberme = $request->has('rememberme');
        
        if (Auth::attempt($credentials, $rememberme)) {
            $request->session()->regenerate();
            $request->session()->flash('login_success', true);
            
            return redirect()->intended('/home');
        }

        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'Invalid credentials. Please try again.',
        ]);
    }

    // API Methods
    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
                'expires_at' => now()->addHours(24)->toISOString()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function apiMe(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    public function apiRefresh(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user,
            'expires_at' => now()->addHours(24)->toISOString()
        ]);
    }
}
