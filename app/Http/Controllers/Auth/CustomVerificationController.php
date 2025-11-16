<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyAccountMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class CustomVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('verify');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(Request $request)
    {
        return Auth::user()->hasVerifiedEmail()
            ? response()->json(['success' => true, 'message' => 'Email already verified'])
            : response()->json(['success' => false, 'message' => 'Email verification required']);
    }

    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user || !hash_equals((string) $request->route('hash'), sha1($user->email))) {
            return response()->json(['success' => false, 'message' => 'Invalid verification link'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['success' => true, 'message' => 'Email already verified']);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['success' => true, 'message' => 'Email verified successfully']);
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['success' => true, 'message' => 'Email already verified']);
        }

        $user = $request->user();
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->userid, 'hash' => sha1($user->email)]
        );

        Mail::to($user->email)->send(new VerifyAccountMail($user, $verificationUrl));

        return response()->json(['success' => true, 'message' => 'Verification link sent']);
    }
}
