<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    use ApiResponse;
    public function check()
    {
        try {
            $data = [
                'authenticated' => Auth::check(),
                'user' => Auth::user() ? [
                    'id' => Auth::user()->userid,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email, 
                    'isadmin' => Auth::user()->isadmin,
                    'permissions' => Auth::user()->permissions()->pluck('shortname')
                ] : null
            ];
            return $this->successResponse($data, 'Authentication status retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to check authentication', $e->getMessage(), 500);
        }
    }

    public function permissions()
    {
        if (!Auth::check()) {
            return $this->errorResponse('Unauthorized', null, 401);
        }

        try {
            $permissions = Auth::user()->permissions()->pluck('shortname');
            return $this->successResponse(['permissions' => $permissions], 'User permissions retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch permissions', $e->getMessage(), 500);
        }
    }
}