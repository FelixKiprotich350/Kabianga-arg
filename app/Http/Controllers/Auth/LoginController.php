<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Info(
 *     title="Kabianga ARG Portal API",
 *     version="1.0.0",
 *     description="Research grants management API"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */
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
        return $this->successResponse($request->user(), 'User information retrieved successfully');
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
