<?php

namespace App\Http\Controllers\Proposals;

use App\Http\Controllers\Controller;
use App\Models\ResearchDesignItem; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ResearchdesignController extends Controller
{
     //
     public function postresearchdesignitem(Request $request)
     {
         if(!auth()->user()->haspermission('canmakenewproposal')){
             return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
         }
         
         // Handle both form field names
         $rules = [
             'proposalidfk' => 'required|string',
         ];
         
         $rules['summary'] = 'required|string';
         $rules['indicators'] = 'required|string';
         $rules['verification'] = 'required|string';
         $rules['assumptions'] = 'required|string';
         $rules['goal'] = 'required|string';
         $rules['purpose'] = 'required|string';
 
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return response()->json(['message' => 'Fill all the required Fields!','type'=>'danger'], 400);
         }
   
         $reditem = new ResearchDesignItem();
         
         $reditem->summary = $request->input('summary');
         $reditem->indicators = $request->input('indicators');
         $reditem->verification = $request->input('verification');
         $reditem->assumptions = $request->input('assumptions');
         $reditem->goal = $request->input('goal');
         $reditem->purpose = $request->input('purpose');
         
         $reditem->proposalidfk = $request->input('proposalidfk');
         $reditem->save();
 
         return response()->json(['message'=> 'Item Saved Successfully!!','type'=>'success', 'success' => true, 'id' => $reditem->designid]);
     }
 
     
     public function fetchall(Request $request)
     {
         $proposalId = $request->input('proposalid');
         if ($proposalId) {
             $data = ResearchDesignItem::where('proposalidfk', $proposalId)->get();
         } else {
             $data = ResearchDesignItem::all();
         }
         return response()->json($data);
     }
 
     public function fetchsearch(Request $request)
     {
         $searchTerm = $request->input('search');
         $data = ResearchDesignItem::with('department', 'grantitem', 'themeitem', 'applicant')
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
 
     public function geteditsingleexpenditurepage($id)
     {
         // Find the proposal by ID or fail with a 404 error
         $prop = ResearchDesignItem::findOrFail($id);
         $isreadonlypage = false;
         $isadminmode = true; 
         // Return the view with the proposal data
         return view('pages.proposals.proposalform', compact('prop', 'isreadonlypage', 'isadminmode', 'departments', 'grants', 'themes'));
     }

     public function updateResearchDesign(Request $request, $id)
     {
         $rules = [
             'summary' => 'required|string',
             'indicators' => 'required|string',
             'verification' => 'required|string',
             'assumptions' => 'required|string',
             'goal' => 'required|string',
             'purpose' => 'required|string'
         ];

         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return response()->json(['message' => $validator->errors(), 'type' => 'danger'], 400);
         }

         $reditem = ResearchDesignItem::findOrFail($id);
         $reditem->summary = $request->input('summary');
         $reditem->indicators = $request->input('indicators');
         $reditem->verification = $request->input('verification');
         $reditem->assumptions = $request->input('assumptions');
         $reditem->goal = $request->input('goal');
         $reditem->purpose = $request->input('purpose');
         $reditem->save();

         return response()->json(['message' => 'Research design updated successfully!', 'type' => 'success']);
     }

     public function deleteResearchDesign($id)
     {
         $reditem = ResearchDesignItem::findOrFail($id);
         $reditem->delete();

         return response()->json(['message' => 'Research design deleted successfully!', 'type' => 'success']);
     }
}
