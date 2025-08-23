<?php

namespace App\Http\Controllers;

use App\Models\ResearchTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResearchThemeController extends Controller
{
    public function fetchAllThemes()
    {
        $themes = ResearchTheme::all();
        return response()->json(['data' => $themes]);
    }

    public function index()
    {
        return view('pages.themes.index');
    }

    public function createTheme(Request $request)
    {
        $rules = [
            'themename' => 'required|string|max:255|unique:researchthemes',
            'themedescription' => 'required|string',
            'applicablestatus' => 'required|string|in:Active,Inactive'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'type' => 'error'], 400);
        }

        // Get next available ID
        $nextId = ResearchTheme::max('themeid') + 1;
        
        $theme = ResearchTheme::create([
            'themeid' => $nextId,
            'themename' => $request->themename,
            'themedescription' => $request->themedescription,
            'applicablestatus' => $request->applicablestatus
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Theme created successfully!', 
            'type' => 'success',
            'id' => $theme->themeid
        ]);
    }

    public function updateTheme(Request $request, $id)
    {
        $rules = [
            'themename' => 'required|string|max:255|unique:researchthemes,themename,' . $id . ',themeid',
            'themedescription' => 'required|string',
            'applicablestatus' => 'required|string|in:Active,Inactive'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'type' => 'error'], 400);
        }

        $theme = ResearchTheme::findOrFail($id);
        $theme->update([
            'themename' => $request->themename,
            'themedescription' => $request->themedescription,
            'applicablestatus' => $request->applicablestatus
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Theme updated successfully!', 
            'type' => 'success'
        ]);
    }

    public function deleteTheme($id)
    {
        $theme = ResearchTheme::findOrFail($id);
        $theme->delete();

        return response()->json([
            'success' => true,
            'message' => 'Theme deleted successfully!', 
            'type' => 'success'
        ]);
    }
}