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
    

    
    public function addFunding(Request $request, $projectId)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01'
        ]);
        
        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 400);
        }
        
        try {
            $project = ResearchProject::with(['proposal', 'applicant'])->findOrFail($projectId);
            
            $funding = ResearchFunding::create([
                'createdby' => Auth::user()->userid,
                'researchidfk' => $project->researchid,
                'amount' => $request->amount
            ]);
            
            // Send notification to project owner
            $this->notifyFundingAdded($project, $request->amount);
            
            return $this->successResponse($funding, 'Funding added successfully and user notified');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to add funding', $e->getMessage(), 500);
        }
    }
}
