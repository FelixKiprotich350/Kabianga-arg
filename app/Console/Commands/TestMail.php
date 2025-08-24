<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MailService;
use App\Models\User;

class TestMail extends Command
{
    protected $signature = 'mail:test';
    protected $description = 'Test mail sending';

    public function handle()
    {
        $user = User::first();
        
        if (!$user) {
            $this->error('No users found');
            return;
        }

        $this->info("Testing mail to: {$user->email}");
        
        $result = MailService::send($user, 'system_notification', [
            'title' => 'Test Mail Command',
            'message' => 'This is a test email sent from the command line.',
            'actionUrl' => 'http://localhost:8000',
            'actionText' => 'Visit Site'
        ]);
        
        if ($result) {
            $this->info('Mail sent successfully!');
        } else {
            $this->error('Mail sending failed. Check logs.');
        }
    }
}