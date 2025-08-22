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

    public function createTheme(Request $request)
    {
        if (!auth()->user()->haspermission('canaddoredittheme')) {
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }

        $rules = [
            'themename' => 'required|string|max:255',
            'description' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'type' => 'danger'], 400);
        }

        $theme = new ResearchTheme();
        $theme->themename = $request->input('themename');
        $theme->description = $request->input('description');
        $theme->save();

        return response()->json(['message' => 'Theme created successfully!', 'type' => 'success']);
    }

    public function updateTheme(Request $request, $id)
    {
        if (!auth()->user()->haspermission('canaddoredittheme')) {
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }

        $rules = [
            'themename' => 'required|string|max:255',
            'description' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'type' => 'danger'], 400);
        }

        $theme = ResearchTheme::findOrFail($id);
        $theme->themename = $request->input('themename');
        $theme->description = $request->input('description');
        $theme->save();

        return response()->json(['message' => 'Theme updated successfully!', 'type' => 'success']);
    }

    public function deleteTheme($id)
    {
        if (!auth()->user()->haspermission('candeletetheme')) {
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }

        $theme = ResearchTheme::findOrFail($id);
        $theme->delete();

        return response()->json(['message' => 'Theme deleted successfully!', 'type' => 'success']);
    }
}