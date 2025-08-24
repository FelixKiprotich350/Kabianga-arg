<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailingController;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Psy\Readline\Hoa\Console;

class LoginController extends Controller
{
    //
    public function subpermission(Request $request)
    {
        return response()->json($request);
    }
    public function showLoginForm()
    {
        if (Auth::check()) {
            // return response("authorised");
            return redirect()->route("pages.dashboard");
        } else {
            return view('pages.auth.login');

        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $rememberme = $request->has('rememberme');
        if ($credentials['email'] == "admin@admin.com" && $credentials['password'] == "admin@123") {
            $adminexist = User::where('role', 1)->exists();
            if ($adminexist) {
                return redirect()->route('setupadmin');
            } else {
                // $request->session()->put('user_name', 'Developer'); // Store user name in session
                // $request->session()->put('user_id', 'admin@admin.com');// Store user email in session
                // return redirect()->intended('/dashboard');
            }
        }
        if (Auth::attempt($credentials, $rememberme)) {
            // Authentication passed... 
            $user = Auth::user();

            $request->session()->put('user_name', $user->name); // Store user name in session
            $request->session()->put('user_id', $user->email);// Store user email in session
            $request->session()->flash('login_success', true);
            $request->session()->flash('user_name', $user->name);
          
            // Create an instance of MailingController and call the sendMail function
            // $mailingController = new MailingController();
            // $mailingController->sendMail($recipientEmail, $details);
            return redirect()->intended('/home');
        }


        // Authentication failed...
        return redirect()->route('pages.login')->withInput($request->only('email'))->withErrors([
            'email' => 'Invalid credentials. Please try again.',
        ]);
    }

    // API Methods
    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
                'message' => 'Login successful'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function apiMe(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    public function apiRefresh(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }
}
