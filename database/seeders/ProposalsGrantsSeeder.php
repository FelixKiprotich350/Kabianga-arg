<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProposalsGrantsSeeder extends Seeder
{
    public function run()
    {
        // Get existing users
        $users = DB::table('users')->pluck('userid')->toArray();
        
        if (empty($users)) {
            echo "No users found. Please run SimpleDataSeeder first.\n";
            return;
        }

        // Get or create Financial Year
        $finyear = DB::table('finyears')->where('finyear', '2024/2025')->first();
        if (!$finyear) {
            $finyearId = DB::table('finyears')->insertGetId([
                'finyear' => '2024/2025',
                'startdate' => '2024-07-01',
                'enddate' => '2025-06-30',
                'description' => 'Academic Year 2024-2025',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $finyearId = $finyear->id;
        }

        // Create Grants
        $grants = [];
        for ($i = 1; $i <= 3; $i++) {
            $grantId = Str::uuid();
            $grants[] = $grantId;
            DB::table('grants')->insert([
                'grantid' => $grantId,
                'grantname' => 'Research Grant ' . $i,
                'grantcode' => 'RG' . $i,
                'maxamount' => 500000 * $i,
                'finyearidfk' => $finyearId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create Proposals
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
            $proposalId = Str::uuid();
            DB::table('proposals')->insert([
                'proposalid' => $proposalId,
                'proposaltitle' => $proposalTitles[$i],
                'useridfk' => $users[$i % count($users)],
                'grantidfk' => $grants[$i % count($grants)],
                'budgetamount' => rand(50000, 300000),
                'submittedstatus' => ['DRAFT', 'SUBMITTED', 'SUBMITTED'][$i % 3],
                'receivedstatus' => $i < 7 ? 'RECEIVED' : 'PENDING',
                'approvalstatus' => $i < 5 ? 'APPROVED' : ($i < 8 ? 'PENDING' : 'REJECTED'),
                'allowediting' => false,
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()
            ]);
        }

        echo "Created successfully:\n";
        echo "- 1 Financial Year (2024/2025)\n";
        echo "- 3 Grants (RG1, RG2, RG3)\n";
        echo "- 10 Proposals with various statuses\n";
    }
}