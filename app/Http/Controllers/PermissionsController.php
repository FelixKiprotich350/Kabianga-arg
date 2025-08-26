<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    public function fetchAllPermissions()
    {
        if (!auth()->user()->haspermission('canviewpermissions')) {
            return response()->json(['data' => []]);
        }

        $permissions = Permission::orderBy('priorityno')->get();
        return response()->json(['data' => $permissions]);
    }


}