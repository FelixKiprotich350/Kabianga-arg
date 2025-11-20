<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class LoginController extends Controller
{
    use ApiResponse;
    //

    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($token = JWTAuth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->isactive) {
                return $this->errorResponse('Account is inactive. Please contact administrator.', null, 403);
            }

            return $this->successResponse([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'user' => $user,
            ], 'Login successful');
        }

        return $this->errorResponse('Invalid credentials', null, 401);
    }

    public function apiLogout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return $this->successResponse(null, 'Logged out successfully');
    }

    public function apiMe(Request $request)
    {
        $user = $request->user();
        $user->permissions = $user->getEffectivePermissions();
        
        return $this->successResponse($user, 'User information retrieved successfully');
    }

    public function apiRefresh(Request $request)
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());

        return $this->successResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ], 'Token refreshed successfully');
    }
}
