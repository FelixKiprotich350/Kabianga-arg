<?php

namespace App\Http\Controllers\Proposals;

use App\Http\Controllers\Controller;
use App\Models\ProposalReviewer;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProposalReviewersController extends Controller
{
    use ApiResponse;

    public function fetchall(Request $request)
    {
        try {
            $proposalId = $request->query('proposal_id');

            if (! $proposalId) {
                return $this->errorResponse('Proposal ID is required', null, 400);
            }

            $data = ProposalReviewer::where('proposal_id', $proposalId)
                ->with('reviewer:userid,name,email')
                ->get();

            return $this->successResponse($data, 'Reviewers retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to fetch reviewers', $e->getMessage(), 500);
        }
    }

    public function postreviewer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'proposal_id' => 'required|integer|exists:proposals,proposalid',
                'reviewer_ids' => 'required|array',
                'reviewer_ids.*' => 'required|string|exists:users,userid',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', $validator->errors(), 400);
            }

            // Soft delete all existing reviewers for this proposal
            ProposalReviewer::where('proposal_id', $request->proposal_id)->delete();

            $created = [];
            foreach ($request->reviewer_ids as $reviewerId) {
                // Check if reviewer exists (including soft deleted)
                $reviewer = ProposalReviewer::withTrashed()
                    ->where('proposal_id', $request->proposal_id)
                    ->where('reviewer_id', $reviewerId)
                    ->first();

                if ($reviewer) {
                    // Restore if soft deleted
                    $reviewer->restore();
                    $reviewer->assigned_by = auth()->user()->userid;
                    $reviewer->save();
                } else {
                    // Create new reviewer
                    $reviewer = ProposalReviewer::create([
                        'proposal_id' => $request->proposal_id,
                        'reviewer_id' => $reviewerId,
                        'assigned_by' => auth()->user()->userid,
                    ]);
                }
                $created[] = $reviewer;
            }

            return $this->successResponse($created, 'Reviewers assigned successfully', [], 201);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to assign reviewers', $e->getMessage(), 500);
        }
    }

    public function fetchmaster()
    {
        try {
            $users = \App\Models\User::select('userid', 'name', 'highqualification')
                ->get()
                ->map(function ($user) {
                    return [
                        'userid' => $user->userid,
                        'name' => $user->name,
                        'highqualification' => $user->highqualification ?? 'N/A',
                    ];
                });

            return $this->successResponse($users, 'Reviewers retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to fetch users', $e->getMessage(), 500);
        }
    }

    public function deleteReviewer($id)
    {
        try {
            $reviewer = ProposalReviewer::findOrFail($id);
            $reviewer->delete();

            return $this->successResponse(null, 'Reviewer removed successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to remove reviewer', $e->getMessage(), 500);
        }
    }
}
