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
            "Your research proposal '{$proposal->researchtitle}' has been submitted and is pending review.",
            ['actionUrl' => route('pages.proposals.viewproposal', $proposal->proposalid)]
        );

        // Notify reviewers
        $reviewers = User::where('isadmin', true)
            ->orWhereHas('permissions', fn($q) => $q->where('shortname', 'canreceiveproposal'))
            ->get();

        $this->notify(
            $reviewers,
            'proposal_submitted',
            'New Proposal Submitted',
            "A new research proposal '{$proposal->researchtitle}' has been submitted by {$proposal->applicant->name}.",
            ['actionUrl' => route('pages.proposals.viewproposal', $proposal->proposalid)]
        );
    }

    protected function notifyProposalReceived($proposal)
    {
        $this->notify(
            $proposal->applicant,
            'proposal_received',
            'Proposal Received',
            "Your research proposal '{$proposal->researchtitle}' has been received and is now under review.",
            ['actionUrl' => route('pages.proposals.viewproposal', $proposal->proposalid)]
        );
    }

    protected function notifyProposalApproved($proposal)
    {
        $this->notify(
            $proposal->applicant,
            'proposal_approved',
            'Proposal Approved',
            "Congratulations! Your research proposal '{$proposal->researchtitle}' has been approved.",
            ['actionUrl' => route('pages.proposals.viewproposal', $proposal->proposalid), 'level' => 'success']
        );
    }

    protected function notifyProposalRejected($proposal)
    {
        $this->notify(
            $proposal->applicant,
            'proposal_rejected',
            'Proposal Rejected',
            "Your research proposal '{$proposal->researchtitle}' has been rejected. Please review the comments.",
            ['actionUrl' => route('pages.proposals.viewproposal', $proposal->proposalid), 'level' => 'error']
        );
    }

    protected function notifyChangesRequested($proposal)
    {
        $this->notify(
            $proposal->applicant,
            'changes_requested',
            'Changes Requested',
            "Changes have been requested for your proposal '{$proposal->researchtitle}'. Please review and update.",
            ['actionUrl' => route('pages.proposals.editproposal', $proposal->proposalid), 'level' => 'warning']
        );
    }

    protected function notifyFundingAdded($project, $amount)
    {
        $this->notify(
            $project->applicant,
            'funding_added',
            'Funding Added to Your Project',
            "Funding of KES " . number_format($amount, 2) . " has been added to your research project '{$project->proposal->researchtitle}'.",
            ['actionUrl' => route('pages.projects.viewmyproject', $project->researchid), 'level' => 'success']
        );
    }
}