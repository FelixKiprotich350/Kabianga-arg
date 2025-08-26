<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResearchProjectsSeeder extends Seeder
{
    public function run()
    {
        // Get approved proposals that don't have projects yet
        $approvedProposals = DB::table('proposals')
            ->where('approvalstatus', 'APPROVED')
            ->whereNotIn('proposalid', function($query) {
                $query->select('proposalidfk')->from('researchprojects');
            })
            ->get();

        foreach ($approvedProposals as $proposal) {
            DB::table('researchprojects')->insert([
                'researchnumber' => 'RP-' . date('Y') . '-' . str_pad($proposal->proposalid, 4, '0', STR_PAD_LEFT),
                'proposalidfk' => $proposal->proposalid,
                'projectstatus' => 'ACTIVE',
                'ispaused' => false,
                'supervisorfk' => $proposal->useridfk, // Using proposal owner as supervisor for now
                'fundingfinyearfk' => 1, // Using first financial year
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "Created " . count($approvedProposals) . " research projects from approved proposals.\n";
    }
}