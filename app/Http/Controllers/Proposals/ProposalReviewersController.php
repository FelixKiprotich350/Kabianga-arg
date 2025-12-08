<?php

namespace App\Http\Controllers\Proposals;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalReviewer;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProposalReviewersController extends Controller
{
    use ApiResponse;

    public function assignReviewers(Request $request, $proposalId)
    {
        if (!auth()->user()->haspermission('canassignreviewers')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'reviewer_ids' => 'required|array|min:1',
            'reviewer_ids.*' => 'required|string|exists:users,userid',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $proposal = Proposal::findOrFail($proposalId);

        try {
            $assignedReviewers = [];
            foreach ($request->reviewer_ids as $reviewerId) {
                $reviewer = ProposalReviewer::firstOrCreate(
                    ['proposal_id' => $proposalId, 'reviewer_id' => $reviewerId],
                    ['assigned_by' => auth()->user()->userid]
                );
                $assignedReviewers[] = $reviewer->load('reviewer');
            }

            return response()->json([
                'success' => true,
                'message' => 'Reviewers assigned successfully',
                'data' => $assignedReviewers
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function removeReviewer($proposalId, $reviewerId)
    {
        if (!auth()->user()->haspermission('canassignreviewers')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $deleted = ProposalReviewer::where('proposal_id', $proposalId)
                ->where('reviewer_id', $reviewerId)
                ->delete();

            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Reviewer removed successfully']);
            }

            return response()->json(['success' => false, 'message' => 'Reviewer not found'], 404);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getReviewers($proposalId)
    {
        try {
            $reviewers = ProposalReviewer::with(['reviewer', 'assignedBy'])
                ->where('proposal_id', $proposalId)
                ->get();

            return response()->json(['success' => true, 'data' => $reviewers]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getMyReviewProposals()
    {
        try {
            $userId = auth()->user()->userid;
            $proposals = ProposalReviewer::with(['proposal.applicant', 'proposal.department', 'proposal.themeitem'])
                ->where('reviewer_id', $userId)
                ->get()
                ->pluck('proposal');

            return response()->json(['success' => true, 'data' => $proposals]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
