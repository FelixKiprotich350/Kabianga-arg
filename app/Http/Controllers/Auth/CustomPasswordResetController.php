<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Services\MailService;
use App\Models\User;

class CustomPasswordResetController extends Controller
{
    // Show the form to request a password reset link
    public function showLinkRequestForm()
    {
        return response()->json(['success' => false, 'message' => 'Please use API password reset endpoint']);
    }

    // Handle sending the reset link email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? response()->json(['success' => true, 'message' => 'We have emailed your password reset link!'])
                    : response()->json(['success' => false, 'message' => 'Unable to send reset email. Please try again.'], 400);
    }

    // Show the form to reset the password
    public function showResetForm($token)
    {
        return response()->json(['success' => true, 'data' => ['token' => $token], 'message' => 'Use this token to reset password via API']);
    }

    // Handle resetting the password
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? response()->json(['success' => true, 'message' => 'Password reset successfully'])
                    : response()->json(['success' => false, 'message' => 'Password reset failed'], 400);
    }
}
