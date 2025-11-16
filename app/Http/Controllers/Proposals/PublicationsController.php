<?php

namespace App\Http\Controllers\Proposals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Traits\ApiResponse;
use Exception; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class PublicationsController extends Controller
{
    use ApiResponse;
    //
    public function postpublication(Request $request)
    {
        \Log::info('Publication request received', $request->all());
        
        if(!auth()->user()->haspermission('canmakenewproposal')){
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }
        
        $proposal = \App\Models\Proposal::findOrFail($request->input('proposalidfk'));
        if (!$proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }
        
        $rules = [
            'proposalidfk' => 'required|string',
            'title' => 'required|string',
            'authors' => 'required|string',
            'publisher' => 'required|string',
            'researcharea' => 'required|string',
            'year' => 'required|string',
            'volume' => 'required|string',
            'pages' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => 'Fill all the required Fields!','type'=>'danger'], 400);
        }

        $currentCount = Publication::where('proposalidfk', $request->input('proposalidfk'))->count();
        if($currentCount>=5){
            return response()->json(['message'=>'You have reached the maximum number of Publications allowed!','type'=>'warning']);
        }

        $publication = new Publication();
        $publication->title = $request->input('title');
        $publication->authors = $request->input('authors');
        $publication->publisher = $request->input('publisher');
        $publication->researcharea = $request->input('researcharea');
        $publication->year = $request->input('year');
        $publication->volume = $request->input('volume');
        $publication->pages = $request->input('pages');
        
        $publication->proposalidfk = $request->input('proposalidfk');
        $publication->save();

        return response()->json(['message'=> 'Publication Saved Successfully!!','type'=>'success', 'success' => true, 'id' => $publication->publicationid]);
    }

    public function fetchall(Request $request)
    {
        try {
            $proposalId = $request->input('proposalid');
            if ($proposalId) {
                $data = Publication::where('proposalidfk', $proposalId)->get();
            } else {
                $data = Publication::all();
            }
            return $this->successResponse($data, 'Publications retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to fetch publications', $e->getMessage(), 500);
        }
    }

    public function fetchsearch(Request $request)
    {
        try {
            $searchTerm = $request->input('search');
            $data = Publication::where('title', 'like', '%' . $searchTerm . '%')
                ->orWhere('authors', 'like', '%' . $searchTerm . '%')
                ->orWhere('publisher', 'like', '%' . $searchTerm . '%')
                ->get();
            return $this->successResponse($data, 'Publications search completed successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to search publications', $e->getMessage(), 500);
        }
    }

    public function updatePublication(Request $request, $id)
    {
        if(!auth()->user()->haspermission('canmakenewproposal')){
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }
        
        $publication = Publication::findOrFail($id);
        $proposal = \App\Models\Proposal::findOrFail($publication->proposalidfk);
        if (!$proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }

        $rules = [
            'authors' => 'required|string',
            'year' => 'required|string',
            'pubtitle' => 'required|string',
            'researcharea' => 'required|string',
            'publisher' => 'required|string',
            'volume' => 'required|string',
            'pubpages' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'type' => 'danger'], 400);
        }

        $publication = Publication::findOrFail($id);
        $publication->authors = $request->input('authors');
        $publication->year = $request->input('year');
        $publication->title = $request->input('pubtitle');
        $publication->volume = $request->input('volume');
        $publication->researcharea = $request->input('researcharea');
        $publication->pages = $request->input('pubpages');
        $publication->publisher = $request->input('publisher');
        $publication->save();

        return response()->json(['message' => 'Publication updated successfully!', 'type' => 'success']);
    }

    public function deletePublication($id)
    {
        if(!auth()->user()->haspermission('canmakenewproposal')){
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }
        
        $publication = Publication::findOrFail($id);
        $proposal = \App\Models\Proposal::findOrFail($publication->proposalidfk);
        if (!$proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }

        $publication = Publication::findOrFail($id);
        $publication->delete();

        return response()->json(['message' => 'Publication deleted successfully!', 'type' => 'success']);
    }
}
