<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationType;
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

    public function notifyUsersOfProposalActivity($activityName, $subject, $level, $introLines, $actionText, $actionUrl)
    {
        try {
            if (NotificationType::where('typename', $activityName)->exists()) {
                $notType = NotificationType::where('typename', $activityName)->first();
                $users = $this->getNotificationTypeUsers($notType->typeuuid);
                
                foreach ($users as $user) {
                    $this->notify(
                        $user,
                        $activityName,
                        $subject,
                        implode(' ', $introLines),
                        $actionUrl
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify users of proposal activity', [
                'activity' => $activityName,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function getNotificationTypeUsers($typeId)
    {
        return User::whereHas('notifiabletypes', function ($query) use ($typeId) {
            $query->where('notificationfk', $typeId);
        })->get();
    }

    public function notifyProposalChangeRequest($proposalId)
    {
        // Implementation for proposal change request notification
        $this->notifyUsersOfProposalActivity(
            'proposalchangerequest',
            'Proposal Change Request',
            'info',
            ['A change request has been made for a proposal.'],
            'View Proposal',
            route('pages.proposals.viewproposal', ['id' => $proposalId])
        );
    }

    public function notifyUserReceivedProposal($proposal)
    {
        $user = User::findOrFail($proposal->useridfk);
        $this->notify(
            $user,
            'proposalreceived',
            'Proposal Received',
            'Your proposal has been received and is under review.',
            route('pages.proposals.viewproposal', ['id' => $proposal->proposalid])
        );
    }
}