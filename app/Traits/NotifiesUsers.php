<?php

namespace App\Traits;

use App\Services\SimpleMailService;
use App\Models\User;
use App\Models\Notification;

trait NotifiesUsers
{
    protected function notify($to, string $type, string $title, string $message, array $options = [])
    {
        $users = is_array($to) ? $to : [$to];
        
        foreach ($users as $user) {
            // Create in-app notification
            if ($options['channels']['inapp'] ?? true) {
                Notification::create([
                    'user_id' => $user->userid,
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                    'data' => $options['data'] ?? null
                ]);
            }
            
            // Send email notification
            if ($options['channels']['email'] ?? true) {
                SimpleMailService::send(
                    $user->email, 
                    $title, 
                    $message, 
                    $options['actionUrl'] ?? null, 
                    $options['actionText'] ?? 'View Details'
                );
            }
        }
        
        return true;
    }

    // Proposal notifications
    protected function notifyProposalSubmitted($proposal)
    {
        // Notify submitter
        $this->notify(
            $proposal->applicant,
            'proposal_submitted',
            'Proposal Submitted Successfully',
            "Your research proposal '{$proposal->researchtitle}' has been submitted and is pending review."
        );

        // Notify reviewers
        $reviewers = User::where('isadmin', true)
            ->orWhereHas('permissions', fn($q) => $q->where('shortname', 'canreceiveproposal'))
            ->get();

        $this->notify(
            $reviewers,
            'proposal_submitted',
            'New Proposal Submitted',
            "A new research proposal '{$proposal->researchtitle}' has been submitted by {$proposal->applicant->name}."
        );
    }

    protected function notifyProposalReceived($proposal)
    {
        $this->notify(
            $proposal->applicant,
            'proposal_received',
            'Proposal Received',
            "Your research proposal '{$proposal->researchtitle}' has been received and is now under review."
        );
    }

    protected function notifyProposalApproved($proposal)
    {
        $this->notify(
            $proposal->applicant,
            'proposal_approved',
            'Proposal Approved',
            "Congratulations! Your research proposal '{$proposal->researchtitle}' has been approved.",
            ['level' => 'success']
        );
    }

    protected function notifyProposalRejected($proposal)
    {
        $this->notify(
            $proposal->applicant,
            'proposal_rejected',
            'Proposal Rejected',
            "Your research proposal '{$proposal->researchtitle}' has been rejected. Please review the comments.",
            ['level' => 'error']
        );
    }

    protected function notifyChangesRequested($proposal)
    {
        $this->notify(
            $proposal->applicant,
            'changes_requested',
            'Changes Requested',
            "Changes have been requested for your proposal '{$proposal->researchtitle}'. Please review and update.",
            ['level' => 'warning']
        );
    }

    protected function notifyFundingAdded($project, $amount)
    {
        $this->notify(
            $project->applicant,
            'funding_added',
            'Funding Added to Your Project',
            "Funding of KES " . number_format($amount, 2) . " has been added to your research project '{$project->proposal->researchtitle}'.",
            ['level' => 'success']
        );
    }

    // User Management Notifications
    protected function notifyUserCreated($user, $password = null)
    {
        $message = $password ? 
            "Your account has been created successfully. Your temporary password is: {$password}. Please change it after first login." :
            "Your account has been created successfully. Please check your email for login instructions.";
            
        $this->notify(
            $user,
            'user_created',
            'Welcome to ARG Portal',
            $message,
            ['level' => 'success']
        );
    }

    protected function notifyUserRoleChanged($user, $newRole)
    {
        $this->notify(
            $user,
            'role_changed',
            'Your Role Has Been Updated',
            "Your role has been changed to {$newRole}. This may affect your access permissions.",
            ['level' => 'info']
        );
    }

    protected function notifyUserPermissionsChanged($user)
    {
        $this->notify(
            $user,
            'permissions_changed',
            'Your Permissions Have Been Updated',
            "Your account permissions have been modified. Please review your new access levels.",
            ['level' => 'info']
        );
    }

    protected function notifyUserDisabled($user)
    {
        $this->notify(
            $user,
            'account_disabled',
            'Account Disabled',
            "Your account has been disabled. Please contact the administrator if you believe this is an error.",
            ['level' => 'error', 'channels' => ['email' => true, 'inapp' => false]]
        );
    }

    protected function notifyUserEnabled($user)
    {
        $this->notify(
            $user,
            'account_enabled',
            'Account Reactivated',
            "Your account has been reactivated. You can now access the ARG Portal.",
            ['level' => 'success']
        );
    }

    // Project Management Notifications
    protected function notifyProjectStatusChanged($project, $newStatus)
    {
        $statusMessages = [
            'ACTIVE' => 'Your research project has been activated and is now in progress.',
            'PAUSED' => 'Your research project has been paused. Please contact the administrator for details.',
            'COMPLETED' => 'Congratulations! Your research project has been marked as completed.',
            'CANCELLED' => 'Your research project has been cancelled. Please contact the administrator for details.'
        ];

        $this->notify(
            $project->applicant,
            'project_status_changed',
            'Project Status Updated',
            $statusMessages[$newStatus] ?? "Your project status has been changed to {$newStatus}.",
            ['level' => $newStatus === 'COMPLETED' ? 'success' : 'info']
        );
    }

    protected function notifyProjectAssigned($project, $supervisor)
    {
        // Notify project owner
        $this->notify(
            $project->applicant,
            'project_assigned',
            'Supervisor Assigned to Your Project',
            "A supervisor ({$supervisor->name}) has been assigned to monitor your research project '{$project->proposal->researchtitle}'.",
            ['level' => 'info']
        );

        // Notify supervisor
        $this->notify(
            $supervisor,
            'supervision_assigned',
            'New Project Supervision Assignment',
            "You have been assigned to supervise the research project '{$project->proposal->researchtitle}' by {$project->applicant->name}.",
            ['level' => 'info']
        );
    }

    protected function notifyProgressSubmitted($project, $progress)
    {
        // Notify supervisors and admins
        $supervisors = User::where('isadmin', true)
            ->orWhere('userid', $project->supervisorfk)
            ->get();

        $this->notify(
            $supervisors,
            'progress_submitted',
            'New Progress Report Submitted',
            "A progress report has been submitted for project '{$project->proposal->researchtitle}' by {$project->applicant->name}.",
            ['level' => 'info']
        );
    }

    // System Notifications
    protected function notifySystemMaintenance($users, $message, $scheduledTime = null)
    {
        $fullMessage = $scheduledTime ? 
            "System maintenance is scheduled for {$scheduledTime}. {$message}" : 
            "System maintenance notification: {$message}";

        $this->notify(
            $users,
            'system_maintenance',
            'System Maintenance Notice',
            $fullMessage,
            ['level' => 'warning']
        );
    }

    protected function notifyDeadlineReminder($user, $item, $deadline, $daysLeft)
    {
        $this->notify(
            $user,
            'deadline_reminder',
            'Deadline Reminder',
            "Reminder: Your {$item} deadline is in {$daysLeft} days ({$deadline}). Please ensure timely completion.",
            ['level' => $daysLeft <= 3 ? 'error' : 'warning']
        );
    }

    // Grant and Theme Notifications
    protected function notifyNewGrantAvailable($users, $grant)
    {
        $this->notify(
            $users,
            'new_grant',
            'New Research Grant Available',
            "A new research grant '{$grant->grantname}' is now available for applications. Deadline: {$grant->deadline}.",
            ['level' => 'success']
        );
    }

    protected function notifyThemeUpdated($users, $theme)
    {
        $this->notify(
            $users,
            'theme_updated',
            'Research Theme Updated',
            "The research theme '{$theme->themename}' has been updated. Please review any related proposals.",
            ['level' => 'info']
        );
    }

    // Quick notification helpers
    protected function notifySuccess($user, $title, $message, $actionUrl = null)
    {
        $this->notify($user, 'success', $title, $message, ['actionUrl' => $actionUrl, 'level' => 'success']);
    }

    protected function notifyError($user, $title, $message, $actionUrl = null)
    {
        $this->notify($user, 'error', $title, $message, ['actionUrl' => $actionUrl, 'level' => 'error']);
    }

    protected function notifyInfo($user, $title, $message, $actionUrl = null)
    {
        $this->notify($user, 'info', $title, $message, ['actionUrl' => $actionUrl, 'level' => 'info']);
    }

    protected function notifyWarning($user, $title, $message, $actionUrl = null)
    {
        $this->notify($user, 'warning', $title, $message, ['actionUrl' => $actionUrl, 'level' => 'warning']);
    }
}