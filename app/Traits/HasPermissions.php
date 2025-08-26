<?php

namespace App\Traits;

use App\Services\AccessControlService;

trait HasPermissions
{
    public function hasAnyPermission(array $permissions)
    {
        return AccessControlService::hasAccess($permissions, $this);
    }

    public function hasAllPermissions(array $permissions)
    {
        return AccessControlService::hasAllAccess($permissions, $this);
    }
    
    public function hasAccess($requirement)
    {
        return AccessControlService::hasAccess($requirement, $this);
    }
    
    public function getEffectivePermissions()
    {
        return AccessControlService::getEffectivePermissions($this);
    }
    

}