<?php

namespace App\Http\Controllers;

use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function fetchAllSettings()
    {
        if (!auth()->user()->haspermission('canviewsettings')) {
            return response()->json(['data' => []]);
        }

        $settings = GlobalSetting::all();
        return response()->json(['data' => $settings]);
    }

    public function updateSettings(Request $request)
    {
        if (!auth()->user()->haspermission('canupdatesettings')) {
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }

        $rules = [
            'settings' => 'required|array',
            'settings.*.item' => 'required|string',
            'settings.*.value1' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'type' => 'danger'], 400);
        }

        foreach ($request->input('settings') as $settingData) {
            $setting = GlobalSetting::where('item', $settingData['item'])->first();
            if ($setting) {
                $setting->value1 = $settingData['value1'];
                $setting->save();
            }
        }

        return response()->json(['message' => 'Settings updated successfully!', 'type' => 'success']);
    }
}