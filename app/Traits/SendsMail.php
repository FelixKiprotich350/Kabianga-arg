<?php

namespace App\Traits;

use App\Services\MailService;

trait SendsMail
{
    protected function sendMail($to, string $template, array $data = [])
    {
        return MailService::send($to, $template, $data);
    }

    protected function sendPasswordReset($user, string $token)
    {
        $url = route('password.reset', ['token' => $token]) . '?email=' . urlencode($user->email);
        return $this->sendMail($user, 'password_reset', ['url' => $url]);
    }

    protected function sendEmailVerification($user, string $url)
    {
        return $this->sendMail($user, 'email_verification', ['url' => $url]);
    }

    protected function sendNotification($to, string $title, string $message, array $options = [])
    {
        return $this->sendMail($to, 'system_notification', array_merge([
            'title' => $title,
            'message' => $message
        ], $options));
    }

    protected function sendBulkNotification($users, string $title, string $message, array $options = [])
    {
        return $this->sendMail($users, 'system_notification', array_merge([
            'title' => $title,
            'message' => $message
        ], $options));
    }
}