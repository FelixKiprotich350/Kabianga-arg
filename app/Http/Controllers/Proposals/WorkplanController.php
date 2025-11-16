<?php

namespace App\Http\Controllers\Proposals;


use App\Http\Controllers\Controller;
use App\Models\Workplan;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WorkplanController extends Controller
{
    use ApiResponse;
    //
    public function postworkplanitem(Request $request)
    {
        if(!auth()->user()->haspermission('canmakenewproposal')){
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }
        
        $proposal = \App\Models\Proposal::findOrFail($request->input('proposalidfk'));
        if (!$proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }
        
        // Handle both form field names
        $rules = [
            'proposalidfk' => 'required|string',
        ];
        
        if ($request->has('activityname')) {
            $rules['activityname'] = 'required|string';
            $rules['startdate'] = 'required|date';
            $rules['enddate'] = 'required|date';
            $rules['activitydescription'] = 'required|string';
        } else {
            $rules['activity'] = 'required|string';
            $rules['time'] = 'required|string';
            $rules['input'] = 'required|string';
            $rules['outcome'] = 'required|string';
            $rules['facilities'] = 'required|string';
            $rules['bywhom'] = 'required|string';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => 'Fill all the required Fields!','type'=>'danger'], 400);
        }

        $workplan = new Workplan();
        
        if ($request->has('activityname')) {
            // Store activity name and dates in existing fields
            $workplan->activity = $request->input('activityname');
            $workplan->time = $request->input('startdate') . ' to ' . $request->input('enddate');
            $workplan->input = $request->input('activitydescription');
            $workplan->outcome = 'N/A';
            $workplan->facilities = 'N/A';
            $workplan->bywhom = 'N/A';
        } else {
            $workplan->activity = $request->input('activity');
            $workplan->input = $request->input('input');
            $workplan->bywhom = $request->input('bywhom');
            $workplan->outcome = $request->input('outcome');
            $workplan->facilities = $request->input('facilities');
            $workplan->time = $request->input('time');
        }
        
        $workplan->proposalidfk = $request->input('proposalidfk');
        $workplan->save();

        return response()->json(['message'=> 'WorkplanItem Saved Successfully!!','type'=>'success', 'success' => true, 'id' => $workplan->workplanid]);
    }

    
    public function fetchall(Request $request)
    {
        try {
            $proposalId = $request->input('proposalid');
            if ($proposalId) {
                $data = Workplan::where('proposalidfk', $proposalId)->get();
            } else {
                $data = Workplan::all();
            }
            return $this->successResponse($data, 'Workplan items retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch workplan items', $e->getMessage(), 500);
        }
    }

    public function fetchsearch(Request $request)
    {
        try {
            $searchTerm = $request->input('search');
            $data = Workplan::where('activity', 'like', '%' . $searchTerm . '%')
                ->orWhere('time', 'like', '%' . $searchTerm . '%')
                ->orWhere('input', 'like', '%' . $searchTerm . '%')
                ->orWhere('outcome', 'like', '%' . $searchTerm . '%')
                ->get();
            return $this->successResponse($data, 'Workplan search completed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to search workplan items', $e->getMessage(), 500);
        }
    }



    public function updateWorkplan(Request $request, $id)
    {
        $workplan = Workplan::findOrFail($id);
        $proposal = \App\Models\Proposal::findOrFail($workplan->proposalidfk);
        if (!$proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }
        
        $rules = [
            'activity' => 'required|string',
            'time' => 'required|string',
            'input' => 'required|string',
            'outcome' => 'required|string',
            'facilities' => 'required|string',
            'bywhom' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'type' => 'danger'], 400);
        }

        $workplan = Workplan::findOrFail($id);
        $workplan->activity = $request->input('activity');
        $workplan->input = $request->input('input');
        $workplan->bywhom = $request->input('bywhom');
        $workplan->outcome = $request->input('outcome');
        $workplan->facilities = $request->input('facilities');
        $workplan->time = $request->input('time');
        $workplan->save();

        return response()->json(['message' => 'Workplan updated successfully!', 'type' => 'success']);
    }

    public function deleteWorkplan($id)
    {
        $workplan = Workplan::findOrFail($id);
        $proposal = \App\Models\Proposal::findOrFail($workplan->proposalidfk);
        if (!$proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }
        $workplan->delete();

        return response()->json(['message' => 'Workplan deleted successfully!', 'type' => 'success']);
    }
}
