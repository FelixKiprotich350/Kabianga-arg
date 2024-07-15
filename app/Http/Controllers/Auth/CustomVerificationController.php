<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;


class CustomVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.custom');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        Log::info('Authenticated user:', ['user' => $user]);
        return Auth::user()->hasVerifiedEmail()
            ? redirect()->route('pages.dashboard')
            : view('pages.auth.verifyemail');
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('pages.dashboard');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('pages.dashboard')->with('verified', true);
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('pages.dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('verificationstatus', 'verification-link-sent');
    }
}