<?php

namespace App\Traits;

use App\Services\NotificationService;
use App\Models\User;

trait CreatesNotifications
{
    protected function notifyProposalSubmitted($proposal)
    {
        // Notify committee members and admins
        $recipients = User::where('isadmin', true)
            ->orWhereHas('permissions', function($query) {
                $query->where('shortname', 'canreceiveproposal');
            })
            ->get();

        $actionUrl = route('pages.proposals.viewproposal', ['id' => $proposal->proposalid]);
        
        foreach ($recipients as $user) {
            NotificationService::createWithEmail(
                $user,
                NotificationService::TYPE_PROPOSAL_SUBMITTED,
                'New Proposal Submitted',
                "A new research proposal '{$proposal->researchtitle}' has been submitted by {$proposal->applicant->name}.",
                $actionUrl,
                ['proposal_id' => $proposal->proposalid]
            );
        }
    }

    protected function notifyProposalApproved($proposal)
    {
        $actionUrl = route('pages.proposals.viewproposal', ['id' => $proposal->proposalid]);
        
        NotificationService::createWithEmail(
            $proposal->applicant,
            NotificationService::TYPE_PROPOSAL_APPROVED,
            'Proposal Approved',
            "Your research proposal '{$proposal->researchtitle}' has been approved.",
            $actionUrl,
            ['proposal_id' => $proposal->proposalid]
        );
    }

    protected function notifyProposalRejected($proposal)
    {
        $actionUrl = route('pages.proposals.viewproposal', ['id' => $proposal->proposalid]);
        
        NotificationService::createWithEmail(
            $proposal->applicant,
            NotificationService::TYPE_PROPOSAL_REJECTED,
            'Proposal Rejected',
            "Your research proposal '{$proposal->researchtitle}' has been rejected.",
            $actionUrl,
            ['proposal_id' => $proposal->proposalid]
        );
    }

    protected function notifyProposalChangesRequested($proposal)
    {
        $actionUrl = route('pages.proposals.viewproposal', ['id' => $proposal->proposalid]);
        
        NotificationService::createWithEmail(
            $proposal->applicant,
            NotificationService::TYPE_PROPOSAL_CHANGES_REQUESTED,
            'Changes Requested',
            "Changes have been requested for your proposal '{$proposal->researchtitle}'.",
            $actionUrl,
            ['proposal_id' => $proposal->proposalid]
        );
    }
}