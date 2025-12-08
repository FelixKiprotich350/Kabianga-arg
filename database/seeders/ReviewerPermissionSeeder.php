<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewerPermissionSeeder extends Seeder
{
    public function run()
    {
        $permission = [
            'pid' => \Illuminate\Support\Str::uuid(),
            'menuname' => 'Assign Reviewers',
            'shortname' => 'canassignreviewers',
            'path' => '/proposals/reviewers',
            'priorityno' => 100,
            'permissionlevel' => 2,
            'targetrole' => 2,
            'issuperadminright' => false,
            'description' => 'Can assign reviewers to proposals',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('permissions')->insertOrIgnore($permission);
    }
}
