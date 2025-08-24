<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Services\NotificationService;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // Get first user for testing
        $user = User::first();
        
        if ($user) {
            // Create sample notifications
            NotificationService::createForUser(
                $user,
                NotificationService::TYPE_PROPOSAL_SUBMITTED,
                'New Proposal Submitted',
                'A new research proposal has been submitted for review.'
            );
            
            NotificationService::createForUser(
                $user,
                NotificationService::TYPE_GRANT_AVAILABLE,
                'New Grant Available',
                'A new research grant is now available for applications.'
            );
            
            NotificationService::createForUser(
                $user,
                NotificationService::TYPE_SYSTEM_ANNOUNCEMENT,
                'System Maintenance',
                'The system will undergo maintenance on Sunday from 2:00 AM to 4:00 AM.'
            );
        }
    }
}