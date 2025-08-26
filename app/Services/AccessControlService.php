<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AccessControlService
{
    /**
     * Check if user has access based on permission only
     */
    public static function hasAccess($requirement, User $user = null): bool
    {
        $user = $user ?? Auth::user();
        
        if (!$user) return false;
        
        // Super admin bypass
        if ($user->isadmin) return true;
        
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
        if ($user->isadmin) return true;
        
        return collect($requirements)->every(fn($req) => self::checkSingle($req, $user));
    }
    
    /**
     * Check single requirement (permission only)
     */
    private static function checkSingle($requirement, User $user): bool
    {
        // Permission-based check (string values)
        if (is_string($requirement)) {
            return $user->hasPermissionDynamic($requirement);
        }
        
        return false;
    }
    
    /**
     * Get user's effective permissions (assigned permissions only)
     */
    public static function getEffectivePermissions(User $user = null): array
    {
        $user = $user ?? Auth::user();
        
        if (!$user) return [];
        if ($user->isadmin) return ['*']; // All permissions
        
        // Only user-specific permissions
        return $user->permissions()->pluck('shortname')->toArray();
    }
}