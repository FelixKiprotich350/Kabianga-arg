<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrantsOnlySeeder extends Seeder
{
    public function run()
    {
        // Get or create Financial Year
        $finyear = DB::table('finyears')->first();
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
        for ($i = 1; $i <= 3; $i++) {
            DB::table('grants')->insert([
                'title' => 'Research Grant ' . $i,
                'finyearfk' => $finyearId,
                'status' => 'ACTIVE',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        echo "Created successfully:\n";
        echo "- 1 Financial Year (2024/2025)\n";
        echo "- 3 Grants\n";
    }
}