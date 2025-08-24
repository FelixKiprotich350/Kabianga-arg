<?php

namespace App\Http\Controllers\Proposals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Collaborator;
use Exception; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CollaboratorsController extends Controller
{
    public function getallcollaborators(){

    }
    //
    public function postcollaborator(Request $request)
    {
        \Log::info('Collaborator request received', $request->all());
        
        if(!auth()->user()->haspermission('canmakenewproposal')){
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }
        
        $proposal = \App\Models\Proposal::findOrFail($request->input('proposalidfk'));
        if (!$proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }
        
        $rules = [
            'proposalidfk' => 'required|string',
            'collaboratorname' => 'required|string',
            'institution' => 'required|string',
            'position' => 'required|string',
            'researcharea' => 'required|string',
            'experience' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => 'Fill all the required Fields!','type'=>'danger'], 400);
        }

        $currentcount = Collaborator::where('proposalidfk',$request->input('proposalidfk'))->count();
        if($currentcount>=5){
            return response()->json(['message'=>'You have reached the maximum number of collaborators allowed!','type'=>'warning']);
        }
        
        $collaborator = new Collaborator();
        
        $collaborator->collaboratorname = $request->input('collaboratorname');
        $collaborator->institution = $request->input('institution');
        $collaborator->position = $request->input('position');
        $collaborator->researcharea = $request->input('researcharea');
        $collaborator->experience = $request->input('experience');
        
        $collaborator->proposalidfk = $request->input('proposalidfk');
        $collaborator->save();

        return response()->json(['message'=> 'Collaborator Saved Successfully!!','type'=>'success', 'success' => true, 'id' => $collaborator->collaboratorid]);
    }

    public function fetchall(Request $request)
    {
        $proposalId = $request->input('proposalid');
        if ($proposalId) {
            $data = Collaborator::where('proposalidfk', $proposalId)->get();
        } else {
            $data = Collaborator::all();
        }
        return response()->json($data);
    }

    public function fetchsearch(Request $request)
    {
        $searchTerm = $request->input('search');
        $data = Collaborator::with('department', 'grantitem', 'themeitem', 'applicant')
            ->where('approvalstatus', 'like', '%' . $searchTerm . '%')
            ->orWhere('highqualification', 'like', '%' . $searchTerm . '%')
            ->orWhereHas('themeitem', function ($query) use ($searchTerm) {
                $query->where('themename', 'like', '%' . $searchTerm . '%');
            })
            ->orWhereHas('applicant', function ($query1) use ($searchTerm) {
                $query1->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->orWhereHas('department', function ($query) use ($searchTerm) {
                $query->where('shortname', 'like', '%' . $searchTerm . '%');
            })
            ->get();
        return response()->json($data); // Return filtered data as JSON
    }

    public function geteditsinglecollaboratorpage($id)
    {
        // Find the proposal by ID or fail with a 404 error
        $prop = Collaborator::findOrFail($id);
        $isreadonlypage = false;
        $isadminmode = true; 
        // Return the view with the proposal data
        return view('pages.proposals.proposalform', compact('prop', 'isreadonlypage', 'isadminmode', 'departments', 'grants', 'themes'));
    }

    public function updateCollaborator(Request $request, $id)
    {
        if(!auth()->user()->haspermission('canmakenewproposal')){
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }
        
        $collaborator = Collaborator::findOrFail($id);
        $proposal = \App\Models\Proposal::findOrFail($collaborator->proposalidfk);
        if (!$proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }

        $rules = [
            'collaboratorname' => 'required|string',
            'institution' => 'required|string',
            'position' => 'required|string',
            'researcharea' => 'required|string',
            'experience' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'type' => 'danger'], 400);
        }

        $collaborator = Collaborator::findOrFail($id);
        $collaborator->collaboratorname = $request->input('collaboratorname');
        $collaborator->institution = $request->input('institution');
        $collaborator->position = $request->input('position');
        $collaborator->researcharea = $request->input('researcharea');
        $collaborator->experience = $request->input('experience');
        $collaborator->save();

        return response()->json(['message' => 'Collaborator updated successfully!', 'type' => 'success']);
    }

    public function deleteCollaborator($id)
    {
        if(!auth()->user()->haspermission('canmakenewproposal')){
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }
        
        $collaborator = Collaborator::findOrFail($id);
        $proposal = \App\Models\Proposal::findOrFail($collaborator->proposalidfk);
        if (!$proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }

        $collaborator = Collaborator::findOrFail($id);
        $collaborator->delete();

        return response()->json(['message' => 'Collaborator deleted successfully!', 'type' => 'success']);
    }
}
