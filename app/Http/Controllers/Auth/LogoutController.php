<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class LogoutController extends Controller
{
    //
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flash('logout_success', true);
        // Optional: Clear remember me cookies
        $cookie = \Cookie::forget('remember_web');
        return redirect()->route('pages.login')->withCookie($cookie);
    }
}
