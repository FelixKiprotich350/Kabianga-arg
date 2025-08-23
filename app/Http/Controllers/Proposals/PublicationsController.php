<?php

namespace App\Http\Controllers\Proposals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Publication;
use Exception; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class PublicationsController extends Controller
{
    //
    public function postpublication(Request $request)
    {
        if(!auth()->user()->haspermission('canmakenewproposal')){
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }
        
        // Handle both form field names
        $rules = [
            'proposalidfk' => 'required|string',
        ];
        
        if ($request->has('title')) {
            $rules['title'] = 'required|string';
            $rules['authors'] = 'required|string';
            $rules['journal'] = 'required|string';
            $rules['year'] = 'required|integer';
            $rules['type'] = 'required|string';
        } else {
            $rules['authors'] = 'required|string';
            $rules['year'] = 'required|string';
            $rules['pubtitle'] = 'required|string';
            $rules['researcharea'] = 'required|string';
            $rules['publisher'] = 'required|string';
            $rules['volume'] = 'required|string';
            $rules['pubpages'] = 'required|integer';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => 'Fill all the required Fields!','type'=>'danger'], 400);
        }

        $currentCount = Publication::where('proposalidfk', $request->input('proposalidfk'))->count();
        if($currentCount>=5){
            return response()->json(['message'=>'You have reached the maximum number of Publications allowed!','type'=>'warning']);
        }

        $publication = new Publication();
        
        // Handle both field name formats
        if ($request->has('title')) {
            $publication->title = $request->input('title');
            $publication->authors = $request->input('authors');
            $publication->publisher = $request->input('journal');
            $publication->year = $request->input('year');
            $publication->researcharea = $request->input('type');
            $publication->volume = 'N/A';
            $publication->pages = 0;
        } else {
            $publication->authors = $request->input('authors');
            $publication->year = $request->input('year');
            $publication->title = $request->input('pubtitle');
            $publication->volume = $request->input('volume');
            $publication->researcharea = $request->input('researcharea');
            $publication->pages = $request->input('pubpages');
            $publication->publisher = $request->input('publisher');
        }
        
        $publication->proposalidfk = $request->input('proposalidfk');
        $publication->save();

        return response()->json(['message'=> 'Publication Saved Successfully!!','type'=>'success', 'success' => true, 'id' => $publication->publicationid]);
    }

    public function fetchall(Request $request)
    {
        $proposalId = $request->input('proposalid');
        if ($proposalId) {
            $data = Publication::where('proposalidfk', $proposalId)->get();
        } else {
            $data = Publication::all();
        }
        return response()->json($data);
    }

    public function fetchsearch(Request $request)
    {
        $searchTerm = $request->input('search');
        $data = Publication::where('title', 'like', '%' . $searchTerm . '%')
            ->orWhere('authors', 'like', '%' . $searchTerm . '%')
            ->orWhere('publisher', 'like', '%' . $searchTerm . '%')
            ->get();
        return response()->json(['data' => $data]);
    }

    public function updatePublication(Request $request, $id)
    {
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
        $publication = Publication::findOrFail($id);
        $publication->delete();

        return response()->json(['message' => 'Publication deleted successfully!', 'type' => 'success']);
    }
}
