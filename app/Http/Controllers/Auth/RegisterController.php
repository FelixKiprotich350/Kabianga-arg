<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function resetuserpassword(Request $request, $id)
    {
        if (! auth()->user()->haspermission('canresetuserpasswordordisablelogin')) {
            return response()->json(['message' => 'Unauthorized', 'type' => 'danger'], 403);
        }

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successfully!', 'type' => 'success']);
    }

    // API Methods
    public function apiRegister(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:255',
            'pfno' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = new User;
        $user->name = $validatedData['fullname'];
        $user->email = $validatedData['email'];
        $user->pfno = $validatedData['pfno'];
        $user->phonenumber = $validatedData['phonenumber'];
        $user->password = Hash::make($validatedData['password']);
        $user->isadmin = 0;
        $user->isactive = 1;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
        ], 201);
    }
}
