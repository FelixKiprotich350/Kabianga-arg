<?php

namespace App\Http\Controllers;

use App\Models\ExpenditureType;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenditureTypesController extends Controller
{
    use ApiResponse;

    public function fetchallexpendituretypes()
    {
        try {
            $types = ExpenditureType::where('isactive', true)->get();

            return response()->json(['success' => true, 'message' => 'Expenditure types retrieved successfully', 'data' => $types]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch expenditure types', 'data' => []], 500);
        }
    }

    public function store(Request $request)
    {
        if (! auth()->user()->isadmin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $rules = [
            'typename' => 'required|string|max:255|unique:expendituretypes',
            'description' => 'required|string',
            'isactive' => 'boolean',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'data' => $validator->errors()], 400);
        }

        try {
            $type = ExpenditureType::create([
                'typename' => $request->typename,
                'description' => $request->description,
                'isactive' => $request->input('isactive', true),
            ]);

            return response()->json(['success' => true, 'message' => 'Expenditure type created successfully', 'data' => $type]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create expenditure type', 'data' => []], 500);
        }
    }

    public function show($id)
    {
        try {
            $type = ExpenditureType::findOrFail($id);

            return response()->json(['success' => true, 'message' => 'Expenditure type retrieved successfully', 'data' => $type]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Expenditure type not found', 'data' => []], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->isadmin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $rules = [
            'typename' => 'required|string|max:255|unique:expendituretypes,typename,'.$id.',typeid',
            'description' => 'required|string',
            'isactive' => 'boolean',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'data' => $validator->errors()], 400);
        }

        try {
            $type = ExpenditureType::findOrFail($id);
            $type->update([
                'typename' => $request->typename,
                'description' => $request->description,
                'isactive' => $request->input('isactive', $type->isactive),
            ]);

            return response()->json(['success' => true, 'message' => 'Expenditure type updated successfully', 'data' => $type]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update expenditure type', 'data' => []], 500);
        }
    }

    public function destroy($id)
    {
        if (! auth()->user()->isadmin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $type = ExpenditureType::findOrFail($id);
            $type->delete();

            return response()->json(['success' => true, 'message' => 'Expenditure type deleted successfully', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete expenditure type', 'data' => []], 500);
        }
    }
}
