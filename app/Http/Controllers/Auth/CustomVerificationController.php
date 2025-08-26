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
            ? redirect()->route('pages.home')
            : view('pages.auth.verifyemail');
    }

    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user || !hash_equals((string) $request->route('hash'), sha1($user->email))) {
            return redirect()->route('pages.login')->with('error', 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('pages.home')->with('message', 'Email already verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('pages.home')->with('verified', 'Email verified successfully!');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('pages.home');
        }

        $user = $request->user();
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->userid, 'hash' => sha1($user->email)]
        );

        Mail::to($user->email)->send(new VerifyAccountMail($user, $verificationUrl));

        return back()->with('resent', 'Verification link sent!');
    }
}
