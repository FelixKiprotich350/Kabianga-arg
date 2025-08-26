<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MockupDataSeeder extends Seeder
{
    public function run()
    {
        // Create Schools
        $schools = [
            ['schoolid' => Str::uuid(), 'schoolname' => 'School of Science', 'description' => 'Science and Technology'],
            ['schoolid' => Str::uuid(), 'schoolname' => 'School of Business', 'description' => 'Business and Economics'],
            ['schoolid' => Str::uuid(), 'schoolname' => 'School of Education', 'description' => 'Education and Arts'],
        ];
        
        foreach ($schools as $school) {
            DB::table('schools')->insert(array_merge($school, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        // Create Departments
        $departments = [
            ['depid' => Str::uuid(), 'departmentname' => 'Computer Science', 'schoolidfk' => $schools[0]['schoolid']],
            ['depid' => Str::uuid(), 'departmentname' => 'Mathematics', 'schoolidfk' => $schools[0]['schoolid']],
            ['depid' => Str::uuid(), 'departmentname' => 'Business Administration', 'schoolidfk' => $schools[1]['schoolid']],
            ['depid' => Str::uuid(), 'departmentname' => 'Economics', 'schoolidfk' => $schools[1]['schoolid']],
            ['depid' => Str::uuid(), 'departmentname' => 'Educational Psychology', 'schoolidfk' => $schools[2]['schoolid']],
        ];
        
        foreach ($departments as $dept) {
            DB::table('departments')->insert(array_merge($dept, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        // Create Research Themes
        $themes = [
            ['themeid' => Str::uuid(), 'themename' => 'Artificial Intelligence'],
            ['themeid' => Str::uuid(), 'themename' => 'Sustainable Development'],
            ['themeid' => Str::uuid(), 'themename' => 'Educational Technology'],
            ['themeid' => Str::uuid(), 'themename' => 'Financial Innovation'],
        ];
        
        foreach ($themes as $theme) {
            DB::table('researchthemes')->insert(array_merge($theme, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        // Create Financial Year
        $finyear = [
            'finyearid' => Str::uuid(),
            'finyearname' => '2024/2025',
            'startdate' => '2024-07-01',
            'enddate' => '2025-06-30',
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('finyears')->insert($finyear);

        // Create Grant
        $grant = [
            'grantid' => Str::uuid(),
            'grantname' => 'Annual Research Grant 2024',
            'grantcode' => 'ARG2024',
            'maxamount' => 500000,
            'finyearidfk' => $finyear['finyearid'],
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('grants')->insert($grant);

        // Create 10 Users
        $users = [];
        $names = [
            'Dr. John Kamau', 'Prof. Mary Wanjiku', 'Dr. Peter Ochieng', 'Dr. Grace Muthoni',
            'Prof. David Kiprop', 'Dr. Sarah Akinyi', 'Dr. Michael Wekesa', 'Dr. Jane Nyambura',
            'Prof. Samuel Kiprotich', 'Dr. Lucy Wanjiru'
        ];
        
        for ($i = 0; $i < 10; $i++) {
            $userid = Str::uuid();
            $users[] = [
                'userid' => $userid,
                'name' => $names[$i],
                'email' => 'user' . ($i + 1) . '@kabianga.ac.ke',
                'pfno' => 'PF' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'phonenumber' => '0712' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'password' => bcrypt('password123'),
                'isactive' => true,
                'isadmin' => $i === 0, // First user is admin
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }

        // Create 10 Proposals
        $proposalTitles = [
            'Machine Learning Applications in Agriculture',
            'Sustainable Energy Solutions for Rural Communities',
            'Digital Learning Platforms for Primary Education',
            'Microfinance Impact on Small Business Growth',
            'Climate Change Adaptation Strategies',
            'Mobile Health Applications for Maternal Care',
            'Blockchain Technology in Supply Chain Management',
            'Educational Assessment Using AI',
            'Renewable Energy Storage Systems',
            'Community-Based Tourism Development'
        ];

        for ($i = 0; $i < 10; $i++) {
            $proposalid = Str::uuid();
            DB::table('proposals')->insert([
                'proposalid' => $proposalid,
                'proposaltitle' => $proposalTitles[$i],
                'useridfk' => $users[$i]['userid'],
                'grantidfk' => $grant['grantid'],
                'themeidfk' => $themes[$i % 4]['themeid'],
                'departmentidfk' => $departments[$i % 5]['depid'],
                'budgetamount' => rand(50000, 300000),
                'submittedstatus' => ['DRAFT', 'SUBMITTED', 'SUBMITTED'][$i % 3],
                'receivedstatus' => $i < 7 ? 'RECEIVED' : 'PENDING',
                'approvalstatus' => $i < 5 ? 'APPROVED' : ($i < 8 ? 'PENDING' : 'REJECTED'),
                'allowediting' => false,
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()
            ]);
        }

        // Create 5 Research Projects (from approved proposals)
        for ($i = 0; $i < 5; $i++) {
            DB::table('researchprojects')->insert([
                'projectid' => Str::uuid(),
                'projecttitle' => $proposalTitles[$i],
                'useridfk' => $users[$i]['userid'],
                'grantidfk' => $grant['grantid'],
                'budgetamount' => rand(50000, 300000),
                'projectstatus' => ['ACTIVE', 'ACTIVE', 'PAUSED', 'COMPLETED', 'ACTIVE'][$i],
                'startdate' => now()->subMonths(rand(1, 6)),
                'enddate' => now()->addMonths(rand(6, 18)),
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()
            ]);
        }

        echo "Mockup data created successfully!\n";
        echo "- 3 Schools\n";
        echo "- 5 Departments\n";
        echo "- 4 Research Themes\n";
        echo "- 1 Financial Year\n";
        echo "- 1 Grant\n";
        echo "- 10 Users (first user is admin)\n";
        echo "- 10 Proposals\n";
        echo "- 5 Research Projects\n";
    }
}