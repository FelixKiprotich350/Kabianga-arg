<?php

namespace App\Services;

use App\Mail\SimpleMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SimpleMailService
{
    public static function send($to, $subject, $content, $actionUrl = null, $actionText = 'Click Here')
    {
        try {
            $email = is_object($to) ? $to->email : $to;
            
            Mail::to($email)->send(new SimpleMail($subject, $content, $actionUrl, $actionText));
            
            Log::info('Email sent successfully', ['to' => $email, 'subject' => $subject]);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Email failed', ['to' => $email ?? $to, 'error' => $e->getMessage()]);
            return false;
        }
    }
}