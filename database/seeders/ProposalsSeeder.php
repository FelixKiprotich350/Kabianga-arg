<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProposalsSeeder extends Seeder
{
    public function run()
    {
        // Get existing data
        $users = DB::table('users')->get();
        $grants = DB::table('grants')->get();
        $departments = DB::table('departments')->get();
        $themes = DB::table('researchthemes')->get();

        if ($users->isEmpty() || $grants->isEmpty()) {
            echo "Missing required data. Please run other seeders first.\n";
            return;
        }

        // Use first department and theme if they exist, otherwise create minimal data
        $departmentId = $departments->first()->depid ?? null;
        $themeId = $themes->first()->themeid ?? 1;

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

        // Get existing proposal count to continue numbering
        $existingCount = DB::table('proposals')->count();
        
        for ($i = 0; $i < 10; $i++) {
            $user = $users[$i % $users->count()];
            $grant = $grants[$i % $grants->count()];
            
            DB::table('proposals')->insert([
                'proposalcode' => 'PROP' . str_pad($existingCount + $i + 1, 4, '0', STR_PAD_LEFT),
                'grantnofk' => $grant->grantid,
                'departmentidfk' => $departmentId,
                'useridfk' => $user->userid,
                'pfnofk' => $user->pfno,
                'themefk' => $themeId,
                'submittedstatus' => ['PENDING', 'SUBMITTED'][$i % 2],
                'receivedstatus' => $i < 3 ? 'RECEIVED' : 'PENDING',
                'approvalstatus' => ['DRAFT', 'PENDING', 'APPROVED'][$i % 3],
                'allowediting' => true,
                'highqualification' => 'PhD',
                'officephone' => '0202' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'cellphone' => $user->phonenumber,
                'faxnumber' => '0202' . str_pad($i + 100, 6, '0', STR_PAD_LEFT),
                'researchtitle' => $proposalTitles[$i],
                'commencingdate' => now()->addDays(30),
                'terminationdate' => now()->addMonths(12),
                'objectives' => 'Research objectives for ' . $proposalTitles[$i],
                'hypothesis' => 'Research hypothesis for ' . $proposalTitles[$i],
                'significance' => 'Research significance for ' . $proposalTitles[$i],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()
            ]);
        }

        echo "Created 10 proposals successfully! Total proposals: " . DB::table('proposals')->count() . "\n";
    }
}