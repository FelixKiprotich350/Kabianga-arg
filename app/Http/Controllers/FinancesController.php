<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResearchFunding;
use App\Models\ResearchProject;
use App\Traits\ApiResponse;
use App\Traits\NotifiesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FinancesController extends Controller
{
    use ApiResponse, NotifiesUsers;
    
    public function approveFundingRequest(Request $request, $fundingId)
    {
        try {
            $funding = ResearchFunding::with(['project.proposal', 'project.applicant'])->findOrFail($fundingId);
            
            // Check if user has permission to approve funding
            if (!auth()->user()->hasPermission('canmanageprojectfunding')) {
                return $this->errorResponse('Unauthorized', 'You do not have permission to approve funding requests', 403);
            }
            
            // Update funding status to approved (assuming there's a status field)
            $funding->status = 'approved';
            $funding->approved_by = Auth::user()->userid;
            $funding->approved_at = now();
            $funding->save();
            
            // Notify project owner
            $this->notifyFundingApproved($funding->project, $funding->amount);
            
            return $this->successResponse($funding, 'Funding request approved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to approve funding request', $e->getMessage(), 500);
        }
    }
    
    public function getFinanceSummary()
    {
        if (!auth()->user()->hasPermission('canmanageprojectfunding')) {
            return $this->errorResponse('Unauthorized', 'You do not have permission to view finance summary', 403);
        }
        
        $totalReleased = ResearchFunding::sum('amount');
        $totalRequests = ResearchFunding::count();
        $totalProjects = ResearchProject::count();
        $activeProjects = ResearchProject::where('projectstatus', 'ACTIVE')->count();
        $completedProjects = ResearchProject::where('projectstatus', 'COMPLETED')->count();
        $totalGrants = \App\Models\Grant::count();
        
        return $this->successResponse([
            'total_released' => $totalReleased,
            'total_requests' => $totalRequests,
            'pending_requests' => $totalRequests,
            'approved_requests' => $totalRequests,
            'total_projects' => $totalProjects,
            'active_projects' => $activeProjects,
            'completed_projects' => $completedProjects,
            'total_grants' => $totalGrants
        ]);
    }
    
    public function getAllRequests()
    {
        if (!auth()->user()->hasPermission('canmanageprojectfunding')) {
            return $this->errorResponse('Unauthorized', 'You do not have permission to view funding requests', 403);
        }
        
        $requests = ResearchFunding::with(['project.proposal.applicant', 'applicant'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return $this->successResponse($requests);
    }
    
    public function getBudgetAllocation()
    {
        if (!auth()->user()->hasPermission('canmanageprojectfunding')) {
            return $this->errorResponse('Unauthorized', 'You do not have permission to view budget allocation', 403);
        }
        
        $allocations = ResearchProject::with(['proposal.applicant'])
            ->selectRaw('researchprojects.*, COALESCE(SUM(researchfundings.amount), 0) as total_funding')
            ->leftJoin('researchfundings', 'researchprojects.researchid', '=', 'researchfundings.researchidfk')
            ->groupBy('researchprojects.researchid')
            ->get();
            
        return $this->successResponse($allocations);
    }
}
