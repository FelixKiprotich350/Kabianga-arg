<?php

namespace App\Http\Controllers;

use App\Models\ResearchTheme;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResearchThemeController extends Controller
{
    use ApiResponse;
    public function fetchAllThemes()
    {
        try {
            $themes = ResearchTheme::all();
            return $this->successResponse($themes, 'Research themes retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch themes', $e->getMessage(), 500);
        }
    }



    public function createTheme(Request $request)
    {
        if (!auth()->user()->isadmin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $rules = [
            'themename' => 'required|string|max:255|unique:researchthemes',
            'themedescription' => 'required|string',
            'applicablestatus' => 'required|string|in:Active,Inactive'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 400);
        }

        try {
            // Get next available ID
            $nextId = ResearchTheme::max('themeid') + 1;
            
            $theme = ResearchTheme::create([
                'themeid' => $nextId,
                'themename' => $request->themename,
                'themedescription' => $request->themedescription,
                'applicablestatus' => $request->applicablestatus
            ]);

            return $this->successResponse($theme, 'Theme created successfully!');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create theme', $e->getMessage(), 500);
        }
    }

    public function updateTheme(Request $request, $id)
    {
        if (!auth()->user()->isadmin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $rules = [
            'themename' => 'required|string|max:255|unique:researchthemes,themename,' . $id . ',themeid',
            'themedescription' => 'required|string',
            'applicablestatus' => 'required|string|in:Active,Inactive'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 400);
        }

        try {
            $theme = ResearchTheme::findOrFail($id);
            $theme->update([
                'themename' => $request->themename,
                'themedescription' => $request->themedescription,
                'applicablestatus' => $request->applicablestatus
            ]);

            return $this->successResponse($theme, 'Theme updated successfully!');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update theme', $e->getMessage(), 500);
        }
    }

    public function deleteTheme($id)
    {
        if (!auth()->user()->isadmin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        try {
            $theme = ResearchTheme::findOrFail($id);
            $theme->delete();

            return $this->successResponse(null, 'Theme deleted successfully!');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete theme', $e->getMessage(), 500);
        }
    }
}