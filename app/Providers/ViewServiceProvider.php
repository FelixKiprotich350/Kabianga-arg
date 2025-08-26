<?php
// app/Providers/ViewServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Permissions;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Use a closure based composer...
        View::composer('layouts.master', function ($view) {
            // Fetch the userId parameter passed to the view composer
            // Fetch permissions for the current logged-in user
            $user = Auth::user();
            if ($user) {
                $allpermissions = $user->permissions()->orderBy('permissionlevel')->get();
                $menupermissions = $allpermissions->where('permissionlevel', 1);
            } else {
                $allpermissions = collect();
                $menupermissions = collect();
            }

            // Pass the data to the view
            $view->with('permissions', $allpermissions)->with('menu', $menupermissions);
        });
    }

    public function register()
    {
        //
    }
}
