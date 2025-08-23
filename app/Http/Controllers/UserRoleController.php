<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserRoleController extends Controller
{
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role_type' => 'required|in:researcher,committee_member,admin',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'notes' => 'nullable|string'
        ]);

        UserRole::create([
            'user_id' => $userId,
            'role_type' => $request->role_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes
        ]);

        return response()->json(['success' => true, 'message' => 'Role assigned successfully']);
    }

    public function deactivateRole($roleId)
    {
        $role = UserRole::findOrFail($roleId);
        $role->update(['is_active' => false, 'end_date' => now()]);

        return response()->json(['success' => true, 'message' => 'Role deactivated']);
    }

    public function getUserRoles($userId)
    {
        $roles = UserRole::where('user_id', $userId)
                        ->orderBy('start_date', 'desc')
                        ->get();

        return response()->json(['success' => true, 'data' => $roles]);
    }
}