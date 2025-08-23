<?php

use App\Services\AccessControlService;

if (!function_exists('hasAccess')) {
    function hasAccess($requirement)
    {
        return AccessControlService::hasAccess($requirement);
    }
}

if (!function_exists('hasAllAccess')) {
    function hasAllAccess(array $requirements)
    {
        return AccessControlService::hasAllAccess($requirements);
    }
}

if (!function_exists('userCan')) {
    function userCan($permission)
    {
        return auth()->check() && auth()->user()->haspermission($permission);
    }
}

if (!function_exists('userHasRole')) {
    function userHasRole($role)
    {
        return auth()->check() && auth()->user()->role == $role;
    }
}