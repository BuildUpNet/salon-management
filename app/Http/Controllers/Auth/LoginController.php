<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\BranchOtpMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Dotenv\Exception\ValidationException;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $loginInput = $request->input('login');
        $password = $request->input('password');

        $user = User::where('email', $loginInput)
            ->orWhere('otp_email', $loginInput)
            ->first();

        if (!$user) {
            return back()->withErrors(['login' => 'User not found.']);
        }

        if ($user->status != 1) {
            return back()->withErrors(['login' => 'Account not approved.']);
        }

        if (!Hash::check($password, $user->password)) {
            return back()->withErrors(['login' => 'Incorrect password.']);
        }

        if ($user->role === 'branch') {
    // ✅ Temporarily disabled OTP logic
    // if (!$user->otp_email) {
    //     return back()->withErrors(['login' => 'Branch OTP email not configured.']);
    // }

    // $otp = rand(100000, 999999);
    // $user->otp = $otp;
    // $user->otp_expires_at = now()->addMinutes(2);
    // $user->save();

    // Mail::to($user->otp_email)->send(new BranchOtpMail($otp));

    // return redirect()->route('otp.verify.form')->with('otp_user_id', $user->id);

    // ✅ Direct login without OTP
    Auth::login($user);
    return redirect()->route('branch.dashboard');
}


        // For other roles
        Auth::login($user);
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'emp' => redirect()->route('emp-dashbord'),
            default => back()->withErrors(['login' => 'Unknown role.']),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
   public function otpLogin(Request $request)
{
    $request->validate([
        'login' => 'required|string',
        'password' => 'required|string|min:6',
    ]);

    $user = User::where('email', $request->login)
        ->orWhere('otp_email', $request->login)
        ->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    if ($user->role === 'admin') {
        Auth::login($user);
        return response()->json([
            'success' => true,
            'role' => 'admin',
            'redirect' => route('admin.dashboard'),
        ]);
    }

    if ($user->role === 'emp') {
        Auth::login($user);
        return response()->json([
            'success' => true,
            'role' => 'emp',
            'redirect' => route('emp-dashbord'),
        ]);
    }

    if ($user->role === 'branch') {
    // ✅ Temporarily disabled OTP logic
    // $otp = rand(100000, 999999);
    // $user->otp = $otp;
    // $user->otp_expires_at = now()->addMinutes(2);
    // $user->save();
    // Mail::to($user->otp_email)->send(new BranchOtpMail($otp));

    // return response()->json([
    //     'success' => true,
    //     'role' => 'branch',
    //     'login_id' => $user->id,
    //     'message' => 'OTP sent to branch email.',
    // ]);

    // ✅ Direct login without OTP
    Auth::login($user);
    return response()->json([
        'success' => true,
        'role' => 'branch',
        'redirect' => route('branch.dashboard'),
    ]);
}

    return response()->json([
        'success' => false,
        'message' => 'Role not supported.',
    ], 403);
}

   public function verifyOtp(Request $request)
{
    $request->validate([
        'login_id' => 'required|exists:users,id',
        'otp' => 'required|digits:6',
    ]);

    $user = User::find($request->login_id);

    if (!$user || $user->otp !== $request->otp || now()->greaterThan($user->otp_expires_at)) {
        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    $user->otp = null;
    $user->otp_expires_at = null;
    $user->save();

    Auth::login($user);
    return redirect()->route('branch.dashboard');
}


}
