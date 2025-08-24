<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get current authenticated user (you'll need to modify this with your user ID)
$user = App\Models\User::where('email', 'your-email@example.com')->first(); // Replace with your email

if ($user) {
    echo "User: " . $user->name . " (" . $user->email . ")\n";
    echo "Is Admin: " . ($user->isadmin ? 'Yes' : 'No') . "\n";
    echo "Role: " . $user->role . "\n";
    echo "\nPermissions:\n";
    
    $permissions = $user->permissions()->get();
    foreach ($permissions as $permission) {
        echo "- " . $permission->shortname . " (" . $permission->name . ")\n";
    }
    
    echo "\nChecking specific permission:\n";
    echo "canmakenewproposal: " . ($user->haspermission('canmakenewproposal') ? 'Yes' : 'No') . "\n";
} else {
    echo "User not found\n";
}