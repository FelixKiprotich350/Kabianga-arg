<?php

namespace App\Traits;

trait HasPermissions
{
    public function hasAnyPermission(array $permissions)
    {
        if ($this->isadmin) return true;
        
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function hasAllPermissions(array $permissions)
    {
        if ($this->isadmin) return true;
        
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }
}