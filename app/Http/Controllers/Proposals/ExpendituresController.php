<?php

namespace App\Http\Controllers\Proposals;

use App\Http\Controllers\Controller;
use App\Models\Expenditureitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpendituresController extends Controller
{
    //
    public function postexpenditure(Request $request)
    {
        $proposal = \App\Models\Proposal::findOrFail($request->input('proposalidfk'));
        
        // Check if user owns the proposal
        if (auth()->user()->userid !== $proposal->useridfk) {
            return response()->json(['message' => 'Unauthorized - You can only edit your own proposals', 'type' => 'danger'], 403);
        }
        
        // Check if proposal can be edited
        if (!$proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }
        
        // Handle both form field names
        $rules = [
            'proposalidfk' => 'required|string',
        ];
        
        if ($request->has('category')) {
            $rules['category'] = 'required|string';
            $rules['description'] = 'required|string';
            $rules['amount'] = 'required|numeric';
        } else {
            $rules['itemtype'] = 'required|string';
            $rules['item'] = 'required|string';
            $rules['total'] = 'required|numeric|regex:/^\d+(\.\d{1,2})?$/';
            $rules['quantity'] = 'required|integer';
            $rules['unitprice'] = 'required|integer';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => 'Fill all the required Fields!', 'type' => 'danger'], 400);
        }

        // Handle both field name formats
        $itemtype = $request->input('category') ?? $request->input('itemtype');
        $total = $request->input('amount') ?? $request->input('total');
        
        if (!$this->isvalidbudget($request->input('proposalidfk'), $total, $itemtype)) {
            return response()->json(['message' => 'The 60/40 Rule failed to Validate!', 'type' => 'danger'], 400);
        }

        $expenditure = new Expenditureitem();
        
        if ($request->has('category')) {
            $expenditure->itemtype = $request->input('category');
            $expenditure->item = $request->input('description');
            $expenditure->total = $request->input('amount');
            $expenditure->unitprice = $request->input('amount');
            $expenditure->quantity = 1;
        } else {
            $expenditure->itemtype = $request->input('itemtype');
            $expenditure->total = $request->input('total');
            $expenditure->unitprice = $request->input('unitprice');
            $expenditure->quantity = $request->input('quantity');
            $expenditure->item = $request->input('item');
        }
        
        $expenditure->proposalidfk = $request->input('proposalidfk');
        $expenditure->save();

        return response()->json(['message' => 'Expenditure Saved Successfully!!', 'type' => 'success', 'success' => true, 'id' => $expenditure->expenditureid]);
    }


    public function fetchall(Request $request)
    {
        $proposalId = $request->input('proposalid');
        if ($proposalId) {
            $data = Expenditureitem::where('proposalidfk', $proposalId)->get();
            
            $totalBudget = $data->sum('total');
            $rule_40 = $data->whereIn('itemtype', ['Travel/Other', 'Travels', 'Personnel/Subsistence'])->sum('total');
            $rule_60 = $data->whereIn('itemtype', ['Facilities/Equipment', 'Consumables'])->sum('total');
            $isCompliant = $totalBudget > 0 ? ($rule_60 >= (0.6 * $totalBudget)) : true;
            
            return response()->json([
                'items' => $data,
                'is_compliant' => $isCompliant,
                'total_budget' => $totalBudget
            ]);
        } else {
            $data = Expenditureitem::all();
            return response()->json($data);
        }
    }

    public function fetchsearch(Request $request)
    {
        $searchTerm = $request->input('search');
        $proposalId = $request->input('proposalid');
        
        $query = Expenditureitem::query();
        
        // Filter by proposal if provided
        if ($proposalId) {
            $query->where('proposalidfk', $proposalId);
        }
        
        // Apply search filters
        $data = $query->where(function($q) use ($searchTerm) {
                $q->where('itemtype', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item', 'like', '%' . $searchTerm . '%')
                  ->orWhere('total', 'like', '%' . $searchTerm . '%');
            })
            ->get();
            
        return response()->json($data);
    }

    public function geteditsingleexpenditurepage($id)
    {
        // Find the proposal by ID or fail with a 404 error
        $prop = Expenditureitem::findOrFail($id);
        $isreadonlypage = false;
        $isadminmode = true;
        // Return the view with the proposal data
        return view('pages.proposals.proposalform', compact('prop', 'isreadonlypage', 'isadminmode', 'departments', 'grants', 'themes'));
    }
    public function isvalidbudget($id, $newexpenditure, $newexpendituretype)
    {
        $data = Expenditureitem::where('proposalidfk', $id)
            ->get();
        $summary = [];
        $totalOthers = $data->where('itemtype', 'Others')
            ->sum('total');

        $totalTravels = $data->where('itemtype', 'Travels')
            ->sum('total');

        $totalConsumables = $data->where('itemtype', 'Consumables')
            ->sum('total');

        $totalFacilities = $data->where('itemtype', 'Facilities')
            ->sum('total');
        $summary['totalOthers'] = $totalOthers;
        $summary['totalTravels'] = $totalTravels;
        $summary['totalConsumables'] = $totalConsumables;
        $summary['totalFacilities'] = $totalFacilities;
        $summary['totalExpenditures'] = $totalFacilities + $totalConsumables + $totalTravels + $totalOthers;
        $rule_40 = $totalOthers + $totalTravels;
        $rule_60 = $totalFacilities + $totalConsumables;
        if ($newexpendituretype == 'Facilities' || $newexpendituretype == 'Consumables') {
            $rule_60 += $newexpenditure;
        }
        if ($newexpendituretype == 'Travels' || $newexpendituretype == 'Others') {
            $rule_40 += $newexpenditure;
        }
        $total = $rule_40 + $rule_60;
        if ($rule_40 <= (0.4 * $total)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateExpenditure(Request $request, $id)
    {
        $expenditure = Expenditureitem::findOrFail($id);
        $proposal = \App\Models\Proposal::findOrFail($expenditure->proposalidfk);
        if (!$proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }
        
        $rules = [
            'itemtype' => 'required|string',
            'item' => 'required|string',
            'total' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'quantity' => 'required|integer',
            'unitprice' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'type' => 'danger'], 400);
        }

        $expenditure = Expenditureitem::findOrFail($id);
        $expenditure->itemtype = $request->input('itemtype');
        $expenditure->total = $request->input('total');
        $expenditure->unitprice = $request->input('unitprice');
        $expenditure->quantity = $request->input('quantity');
        $expenditure->item = $request->input('item');
        $expenditure->save();

        return response()->json(['message' => 'Expenditure updated successfully!', 'type' => 'success']);
    }

    public function deleteExpenditure($id)
    {
        $expenditure = Expenditureitem::findOrFail($id);
        $proposal = \App\Models\Proposal::findOrFail($expenditure->proposalidfk);
        if (!$proposal->isEditable()) {
            return response()->json(['message' => 'This proposal cannot be edited at this time.', 'type' => 'danger'], 403);
        }
        $expenditure->delete();

        return response()->json(['message' => 'Expenditure deleted successfully!', 'type' => 'success']);
    }


}
