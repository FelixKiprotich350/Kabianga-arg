<?php

namespace App\Http\Controllers\Proposals;

use App\Http\Controllers\Controller;
use App\Models\ResearchDesignItem;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResearchdesignController extends Controller
{
    use ApiResponse;

    //
    public function postresearchdesignitem(Request $request)
    {
        

        $proposal = \App\Models\Proposal::findOrFail($request->input('proposalidfk'));
        if (! $proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
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
            return response()->json(['message' => 'Fill all the required Fields!', 'type' => 'danger'], 400);
        }

        $reditem = new ResearchDesignItem;

        $reditem->summary = $request->input('summary');
        $reditem->indicators = $request->input('indicators');
        $reditem->verification = $request->input('verification');
        $reditem->assumptions = $request->input('assumptions');
        $reditem->goal = $request->input('goal');
        $reditem->purpose = $request->input('purpose');

        $reditem->proposalidfk = $request->input('proposalidfk');
        $reditem->save();

        return response()->json(['message' => 'Item Saved Successfully!!', 'type' => 'success', 'success' => true, 'id' => $reditem->designid]);
    }

    public function fetchall(Request $request)
    {
        try {
            $proposalId = $request->input('proposalid');
            if ($proposalId) {
                $data = ResearchDesignItem::where('proposalidfk', $proposalId)->get();
            } else {
                $data = ResearchDesignItem::all();
            }

            return $this->successResponse($data, 'Research design items retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch research design items', $e->getMessage(), 500);
        }
    }

    public function fetchsearch(Request $request)
    {
        try {
            $searchTerm = $request->input('search');
            $data = ResearchDesignItem::where('summary', 'like', '%'.$searchTerm.'%')
                ->orWhere('indicators', 'like', '%'.$searchTerm.'%')
                ->orWhere('verification', 'like', '%'.$searchTerm.'%')
                ->orWhere('assumptions', 'like', '%'.$searchTerm.'%')
                ->orWhere('goal', 'like', '%'.$searchTerm.'%')
                ->orWhere('purpose', 'like', '%'.$searchTerm.'%')
                ->get();

            return $this->successResponse($data, 'Research design search completed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to search research design items', $e->getMessage(), 500);
        }
    }

    public function updateResearchDesign(Request $request, $id)
    {
        $reditem = ResearchDesignItem::findOrFail($id);
        $proposal = \App\Models\Proposal::findOrFail($reditem->proposalidfk);
        if (! $proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }

        $rules = [
            'summary' => 'required|string',
            'indicators' => 'required|string',
            'verification' => 'required|string',
            'assumptions' => 'required|string',
            'goal' => 'required|string',
            'purpose' => 'required|string',
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
        $proposal = \App\Models\Proposal::findOrFail($reditem->proposalidfk);
        if (! $proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }
        $reditem->delete();

        return response()->json(['message' => 'Research design deleted successfully!', 'type' => 'success']);
    }
}
