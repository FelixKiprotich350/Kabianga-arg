<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\GeneralProposalAction;
use Illuminate\Support\Facades\Log;

class DualNotificationService
{
    public static function notify(User $user, string $type, string $title, string $message, ?string $actionUrl = null, ?array $data = null)
    {
        try {
            $inAppNotification = null;
            
            // Create in-app notification if enabled
            if ($user->inapp_notifications ?? true) {
                $inAppNotification = Notification::create([
                    'user_id' => $user->userid,
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                    'data' => $data
                ]);
            }

            // Send email notification if enabled
            if ($user->email_notifications ?? true) {
                self::sendEmail($user, $title, $message, $actionUrl);
            }

            return $inAppNotification;
        } catch (\Exception $e) {
            Log::error('Notification failed', [
                'user_id' => $user->userid,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public static function notifyMultiple(array $users, string $type, string $title, string $message, ?string $actionUrl = null, ?array $data = null)
    {
        $notifications = [];
        foreach ($users as $user) {
            $notifications[] = self::notify($user, $type, $title, $message, $actionUrl, $data);
        }
        return $notifications;
    }

    private static function sendEmail(User $user, string $title, string $message, ?string $actionUrl = null)
    {
        $emailNotification = new GeneralProposalAction(
            $title,
            "Hello {$user->name},",
            'info',
            [$message],
            $actionUrl ?: '#',
            'View Details',
            ['Thank you for using the ARG Portal.'],
            'Best regards, University of Kabianga'
        );

        $user->notify($emailNotification);
    }
}