<?php

namespace App\Http\Controllers;

use App\Models\GlobalSetting;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    use ApiResponse;
    public function fetchAllSettings()
    {
        if (!auth()->user()->haspermission('canviewsettings')) {
            return $this->errorResponse('Unauthorized', null, 403);
        }

        try {
            $settings = GlobalSetting::all();
            return $this->successResponse($settings, 'Settings retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch settings', $e->getMessage(), 500);
        }
    }

    public function updateSettings(Request $request)
    {
        if (!auth()->user()->haspermission('canupdatesettings')) {
            return $this->errorResponse('Unauthorized', null, 403);
        }

        $rules = [
            'settings' => 'required|array',
            'settings.*.item' => 'required|string',
            'settings.*.value1' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 400);
        }

        try {
            foreach ($request->input('settings') as $settingData) {
                $setting = GlobalSetting::where('item', $settingData['item'])->first();
                if ($setting) {
                    $setting->value1 = $settingData['value1'];
                    $setting->save();
                }
            }

            return $this->successResponse(null, 'Settings updated successfully!');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update settings', $e->getMessage(), 500);
        }
    }
}