<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CommonPagesController extends Controller
{
    public function setupadmin()
    {
        $allusers = User::all();
        return response()->json(['success' => true, 'data' => ['users' => $allusers]]);
    }

    public function makeInitialAdmin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,userid',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->isadmin = true;
        $user->save();

        return response()->json(['success' => true, 'message' => 'User has been set as Admin']);
    }
}
