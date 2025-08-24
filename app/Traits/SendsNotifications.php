<?php

namespace App\Traits;

use App\Services\DualNotificationService;
use App\Models\User;

trait SendsNotifications
{
    protected function notifyProposalSubmitted($proposal)
    {
        // Notify submitter
        DualNotificationService::notify(
            $proposal->applicant,
            'proposal_submitted',
            'Proposal Submitted Successfully',
            "Your research proposal '{$proposal->researchtitle}' has been submitted and is pending review.",
            route('pages.proposals.viewproposal', $proposal->proposalid)
        );

        // Notify reviewers
        $reviewers = User::where('isadmin', true)
            ->orWhereHas('permissions', fn($q) => $q->where('shortname', 'canreceiveproposal'))
            ->get();

        DualNotificationService::notifyMultiple(
            $reviewers->toArray(),
            'proposal_submitted',
            'New Proposal Submitted',
            "A new research proposal '{$proposal->researchtitle}' has been submitted by {$proposal->applicant->name}.",
            route('pages.proposals.viewproposal', $proposal->proposalid)
        );
    }

    protected function notifyProposalReceived($proposal)
    {
        DualNotificationService::notify(
            $proposal->applicant,
            'proposal_received',
            'Proposal Received',
            "Your research proposal '{$proposal->researchtitle}' has been received and is now under review.",
            route('pages.proposals.viewproposal', $proposal->proposalid)
        );
    }

    protected function notifyProposalApproved($proposal)
    {
        DualNotificationService::notify(
            $proposal->applicant,
            'proposal_approved',
            'Proposal Approved',
            "Congratulations! Your research proposal '{$proposal->researchtitle}' has been approved.",
            route('pages.proposals.viewproposal', $proposal->proposalid)
        );
    }

    protected function notifyProposalRejected($proposal)
    {
        DualNotificationService::notify(
            $proposal->applicant,
            'proposal_rejected',
            'Proposal Rejected',
            "Your research proposal '{$proposal->researchtitle}' has been rejected. Please review the comments.",
            route('pages.proposals.viewproposal', $proposal->proposalid)
        );
    }

    protected function notifyChangesRequested($proposal)
    {
        DualNotificationService::notify(
            $proposal->applicant,
            'changes_requested',
            'Changes Requested',
            "Changes have been requested for your proposal '{$proposal->researchtitle}'. Please review and update.",
            route('pages.proposals.editproposal', $proposal->proposalid)
        );
    }

    protected function notifyFundingAdded($project, $amount)
    {
        DualNotificationService::notify(
            $project->applicant,
            'funding_added',
            'Funding Added to Your Project',
            "Funding of KES " . number_format($amount, 2) . " has been added to your research project '{$project->proposal->researchtitle}'.",
            route('pages.projects.viewmyproject', $project->researchid)
        );
    }
}