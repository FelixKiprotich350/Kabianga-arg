<?php

namespace App\Traits;

use App\Services\NotificationService;
use App\Models\User;

trait CreatesNotifications
{
    protected function notifyProposalSubmitted($proposal)
    {
        // Notify committee members and admins
        $recipients = User::whereHas('permissions', function($query) {
                $query->where('shortname', 'canreceiveproposal');
            })
            ->get();
        
        foreach ($recipients as $user) {
            NotificationService::createWithEmail(
                $user,
                NotificationService::TYPE_PROPOSAL_SUBMITTED,
                'New Proposal Submitted',
                "A new research proposal '{$proposal->researchtitle}' has been submitted by {$proposal->applicant->name}.",
                null,
                ['proposal_id' => $proposal->proposalid]
            );
        }
    }

    protected function notifyProposalApproved($proposal)
    {
        NotificationService::createWithEmail(
            $proposal->applicant,
            NotificationService::TYPE_PROPOSAL_APPROVED,
            'Proposal Approved',
            "Your research proposal '{$proposal->researchtitle}' has been approved.",
            null,
            ['proposal_id' => $proposal->proposalid]
        );
    }

    protected function notifyProposalRejected($proposal)
    {
        NotificationService::createWithEmail(
            $proposal->applicant,
            NotificationService::TYPE_PROPOSAL_REJECTED,
            'Proposal Rejected',
            "Your research proposal '{$proposal->researchtitle}' has been rejected.",
            null,
            ['proposal_id' => $proposal->proposalid]
        );
    }

    protected function notifyProposalChangesRequested($proposal)
    {
        NotificationService::createWithEmail(
            $proposal->applicant,
            NotificationService::TYPE_PROPOSAL_CHANGES_REQUESTED,
            'Changes Requested',
            "Changes have been requested for your proposal '{$proposal->researchtitle}'.",
            null,
            ['proposal_id' => $proposal->proposalid]
        );
    }
}