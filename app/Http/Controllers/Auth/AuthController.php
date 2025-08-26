<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function check()
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => Auth::user() ? [
                'id' => Auth::user()->userid,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email, 
                'isadmin' => Auth::user()->isadmin,
                'permissions' => Auth::user()->permissions()->pluck('shortname')
            ] : null
        ]);
    }

    public function permissions()
    {
        if (!Auth::check()) {
            return response()->json(['permissions' => []], 401);
        }

        return response()->json([
            'permissions' => Auth::user()->permissions()->pluck('shortname')
        ]);
    }
}