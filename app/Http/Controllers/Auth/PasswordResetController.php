<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash as HashFacade;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            Log::info('Password reset requested for: ' . $request->email);
            
            $status = Password::sendResetLink($request->only('email'));
            
            Log::info('Password reset status: ' . $status);

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['success' => true, 'message' => 'Password reset link sent to your email', 'data' => null]);
            }
            
            $message = match($status) {
                Password::RESET_THROTTLED => 'Please wait before requesting another password reset',
                Password::INVALID_USER => 'No user found with this email address',
                default => 'Unable to send reset email'
            };
            
            return response()->json(['success' => false, 'message' => $message, 'data' => null], 400);
                
        } catch (\Exception $e) {
            Log::error('Password reset failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function validateToken(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
            ]);

            $user = \App\Models\User::where('email', $request->email)->first();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Invalid email', 'data' => null], 400);
            }

            $resetRecord = DB::table('password_resets')
                ->where('email', $request->email)
                ->where('created_at', '>', now()->subMinutes(60))
                ->first();

            if (!$resetRecord) {
                return response()->json(['success' => false, 'message' => 'Invalid or expired token', 'data' => null], 400);
            }

            $tokenValid = HashFacade::check($request->token, $resetRecord->token);

            return response()->json([
                'success' => $tokenValid,
                'message' => $tokenValid ? 'Token is valid' : 'Invalid token',
                'data' => null
            ]);
            
        } catch (\Exception $e) {
            Log::error('Token validation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Validation error', 'data' => null], 500);
        }
    }

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
            ? response()->json(['success' => true, 'message' => 'Password reset successfully', 'data' => null])
            : response()->json(['success' => false, 'message' => 'Invalid token or email', 'data' => null], 400);
    }
}
