<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    use ApiResponse;
    public function fetchAllPermissions()
    {
        if (!auth()->user()->isadmin) {
            return $this->errorResponse('Unauthorized', null, 403);
        }

        try {
            $permissions = Permission::orderBy('priorityno')->get();
            return $this->successResponse($permissions, 'Permissions retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch permissions', $e->getMessage(), 500);
        }
    }


}