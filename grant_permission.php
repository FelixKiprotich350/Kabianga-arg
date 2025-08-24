<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Replace with your email
$userEmail = 'your-email@example.com';
$permissionShortname = 'canmakenewproposal';

$user = App\Models\User::where('email', $userEmail)->first();
$permission = App\Models\Permission::where('shortname', $permissionShortname)->first();

if ($user && $permission) {
    // Check if user already has permission
    if (!$user->permissions()->where('permissionidfk', $permission->permissionid)->exists()) {
        $user->permissions()->attach($permission->permissionid);
        echo "Permission '{$permissionShortname}' granted to {$user->name}\n";
    } else {
        echo "User already has this permission\n";
    }
} else {
    echo "User or permission not found\n";
    if (!$user) echo "User with email {$userEmail} not found\n";
    if (!$permission) echo "Permission {$permissionShortname} not found\n";
}