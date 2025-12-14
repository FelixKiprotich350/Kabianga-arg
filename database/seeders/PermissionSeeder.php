<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Proposal management
            ['shortname' => 'canviewallproposals', 'menuname' => 'View All Proposals', 'description' => 'Can view all proposals'],
            ['shortname' => 'canapproveproposal', 'menuname' => 'Approve Proposals', 'description' => 'Can approve proposals'],
            ['shortname' => 'canrejectproposal', 'menuname' => 'Reject Proposals', 'description' => 'Can reject proposals'],
            ['shortname' => 'canreceiveproposal', 'menuname' => 'Receive Proposals', 'description' => 'Can receive proposals'],
            ['shortname' => 'canchangeproposaleditstatus', 'menuname' => 'Enable/Disable Proposal Edit', 'description' => 'Can enable/disable proposal editing'],
            ['shortname' => 'canassignreviewers', 'menuname' => 'Assign Reviewers', 'description' => 'Can assign Reviewers to proposals'],

            // Project management
            ['shortname' => 'canviewallprojects', 'menuname' => 'View All Projects', 'description' => 'Can view all projects'],
            ['shortname' => 'cancancelresearchproject', 'menuname' => 'Cancel Projects', 'description' => 'Can cancel research projects'],
            ['shortname' => 'cancompleteresearchproject', 'menuname' => 'Complete Projects', 'description' => 'Can complete research projects'],
            ['shortname' => 'canpauseresearchproject', 'menuname' => 'Pause Projects', 'description' => 'Can pause research projects'],
            ['shortname' => 'canresumeresearchproject', 'menuname' => 'Resume Projects', 'description' => 'Can resume research projects'],
            ['shortname' => 'canmanageprojectfunding', 'menuname' => 'Manage Project Funding', 'description' => 'Can manage project funding'],
            ['shortname' => 'canassignmonitoringperson', 'menuname' => 'Assign Monitoring', 'description' => 'Can assign monitoring person'],

            // Reports
            ['shortname' => 'canviewreports', 'menuname' => 'View Reports', 'description' => 'Can view reports'],
            
            //Dashboard
            ['shortname' => 'canviewdashboard', 'menuname' => 'View Dashboard', 'description' => 'Can view dashboard'],
        ];

        foreach ($permissions as $index => $permission) {
            Permission::updateOrCreate(
                ['shortname' => $permission['shortname']],
                [
                    'menuname' => $permission['menuname'],
                    'shortname' => $permission['shortname'],
                    'path' => '/permissions/' . $permission['shortname'],
                    'priorityno' => $index + 1,
                    'permissionlevel' => 1,
                    'targetrole' => 1,
                    'issuperadminright' => false,
                    'description' => $permission['description']
                ]
            );
        }
    }
}