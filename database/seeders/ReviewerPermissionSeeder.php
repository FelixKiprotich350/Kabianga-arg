<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewerPermissionSeeder extends Seeder
{
    public function run()
    {
        $permission = [
            'permissionname' => 'canassignreviewers',
            'description' => 'Can assign reviewers to proposals',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('permissions')->insertOrIgnore($permission);
    }
}
