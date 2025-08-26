<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SimpleDataSeeder extends Seeder
{
    public function run()
    {
        // Create 10 Users
        $users = [];
        $names = [
            'Dr. John Kamau', 'Prof. Mary Wanjiku', 'Dr. Peter Ochieng', 'Dr. Grace Muthoni',
            'Prof. David Kiprop', 'Dr. Sarah Akinyi', 'Dr. Michael Wekesa', 'Dr. Jane Nyambura',
            'Prof. Samuel Kiprotich', 'Dr. Lucy Wanjiru'
        ];
        
        for ($i = 0; $i < 10; $i++) {
            $userid = Str::uuid();
            DB::table('users')->insert([
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
            ]);
        }

        echo "Created 10 users successfully!\n";
        echo "Admin user: user1@kabianga.ac.ke / password123\n";
        echo "Regular users: user2@kabianga.ac.ke to user10@kabianga.ac.ke / password123\n";
    }
}