<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AccessControlService
{
    /**
     * Check if user has access based on role or permission
     */
    public static function hasAccess($requirement, User $user = null): bool
    {
        $user = $user ?? Auth::user();
        
        if (!$user) return false;
        
        // Super admin bypass
        if ($user->isadmin || $user->role == 1) return true;
        
        // Handle array of requirements (OR logic)
        if (is_array($requirement)) {
            return collect($requirement)->some(fn($req) => self::checkSingle($req, $user));
        }
        
        return self::checkSingle($requirement, $user);
    }
    
    /**
     * Check if user has all requirements (AND logic)
     */
    public static function hasAllAccess(array $requirements, User $user = null): bool
    {
        $user = $user ?? Auth::user();
        
        if (!$user) return false;
        if ($user->isadmin || $user->role == 1) return true;
        
        return collect($requirements)->every(fn($req) => self::checkSingle($req, $user));
    }
    
    /**
     * Check single requirement (role or permission)
     */
    private static function checkSingle($requirement, User $user): bool
    {
        // Role-based check (numeric values)
        if (is_numeric($requirement)) {
            return $user->role == $requirement;
        }
        
        // Dynamic role check (string role types)
        if (in_array($requirement, ['committee_member', 'researcher', 'admin'])) {
            return $user->hasActiveRole($requirement);
        }
        
        // Permission-based check (string values)
        if (is_string($requirement)) {
            return $user->hasPermissionDynamic($requirement);
        }
        
        return false;
    }
    
    /**
     * Get user's effective permissions (role + assigned permissions)
     */
    public static function getEffectivePermissions(User $user = null): array
    {
        $user = $user ?? Auth::user();
        
        if (!$user) return [];
        if ($user->isadmin) return ['*']; // All permissions
        
        $permissions = [];
        
        // Add role-based permissions
        $rolePermissions = $user->defaultpermissions()->pluck('shortname')->toArray();
        $permissions = array_merge($permissions, $rolePermissions);
        
        // Add user-specific permissions
        $userPermissions = $user->permissions()->pluck('shortname')->toArray();
        $permissions = array_merge($permissions, $userPermissions);
        
        return array_unique($permissions);
    }
}