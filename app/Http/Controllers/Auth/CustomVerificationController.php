<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;
use App\Services\MailService;
use Illuminate\Support\Facades\URL as UrlGenerator;


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
        return Auth::user()->hasVerifiedEmail()
            ? redirect()->route('pages.index')
            : view('pages.auth.verifyemail');
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('pages.index');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('pages.index')->with('verified', true);
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('pages.index');
        }

        $user = $request->user();
        $url = UrlGenerator::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->userid, 'hash' => sha1($user->email)]
        );
        
        $sent = MailService::send($user, 'email_verification', ['url' => $url]);
        
        return $sent 
            ? back()->with('verificationstatus', 'verification-link-sent')
            : back()->with('error', 'Unable to send verification email. Please try again.');
    }
}
