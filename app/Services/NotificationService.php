<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\ProposalSubmitted;
use App\Notifications\ProposalApprovedNotification;
use App\Notifications\GeneralProposalAction;
use Illuminate\Support\Facades\Notification as LaravelNotification;

class NotificationService
{
    public static function create($userId, $type, $title, $message, $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);
    }
    
    public static function createForUser(User $user, $type, $title, $message, $data = null)
    {
        return self::create($user->userid, $type, $title, $message, $data);
    }
    
    public static function createForMultipleUsers($userIds, $type, $title, $message, $data = null)
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = self::create($userId, $type, $title, $message, $data);
        }
        return $notifications;
    }
    
    public static function createWithEmail(User $user, $type, $title, $message, $actionUrl = null, $data = null)
    {
        // Create in-app notification
        $notification = self::createForUser($user, $type, $title, $message, $data);
        
        // Send email notification
        self::sendEmailNotification($user, $type, $title, $message, $actionUrl);
        
        return $notification;
    }
    
    private static function sendEmailNotification(User $user, $type, $title, $message, $actionUrl = null)
    {
        $greeting = "Hello {$user->name},";
        $introLines = [$message];
        $actionText = 'View Details';
        $outroLines = ['Thank you for using the ARG Portal.'];
        $salutation = 'Best regards, University of Kabianga';
        
        switch ($type) {
            case self::TYPE_PROPOSAL_SUBMITTED:
                $emailNotification = new ProposalSubmitted(
                    $title, $greeting, 'success', $introLines, 
                    $actionUrl, $actionText, $outroLines, $salutation
                );
                break;
                
            case self::TYPE_PROPOSAL_APPROVED:
                $emailNotification = new ProposalApprovedNotification(
                    $title, $greeting, 'success', $introLines,
                    $actionUrl, $actionText, $outroLines, $salutation
                );
                break;
                
            default:
                $emailNotification = new GeneralProposalAction(
                    $title, $greeting, 'info', $introLines,
                    $actionUrl, $actionText, $outroLines, $salutation
                );
                break;
        }
        
        $user->notify($emailNotification);
    }
    
    // Notification types
    const TYPE_PROPOSAL_SUBMITTED = 'proposal_submitted';
    const TYPE_PROPOSAL_APPROVED = 'proposal_approved';
    const TYPE_PROPOSAL_REJECTED = 'proposal_rejected';
    const TYPE_PROPOSAL_CHANGES_REQUESTED = 'proposal_changes_requested';
    const TYPE_PROJECT_DEADLINE = 'project_deadline';
    const TYPE_SYSTEM_ANNOUNCEMENT = 'system_announcement';
    const TYPE_GRANT_AVAILABLE = 'grant_available';
}