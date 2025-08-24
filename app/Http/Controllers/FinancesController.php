<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResearchFunding;
use App\Models\ResearchProject;
use App\Traits\NotifiesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FinancesController extends Controller
{
    use NotifiesUsers;
    
    public function home()
    {
        $history =[];
        return view('pages.finances.index', compact('history'));
    }
    
    public function addFunding(Request $request, $projectId)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid amount'], 400);
        }
        
        $project = ResearchProject::with(['proposal', 'applicant'])->findOrFail($projectId);
        
        $funding = ResearchFunding::create([
            'createdby' => Auth::user()->userid,
            'researchidfk' => $project->researchid,
            'amount' => $request->amount
        ]);
        
        // Send notification to project owner
        $this->notifyFundingAdded($project, $request->amount);
        
        return response()->json([
            'success' => true, 
            'message' => 'Funding added successfully and user notified'
        ]);
    }
}
