<?php

namespace App\Http\Controllers\Branch;

use App\Models\User;
use App\Mail\OtpMail;
use App\Models\AddBranches;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class ProfileController extends Controller
{
    public function settingsPage()
    {
        $user = Auth::user();
        $branch = AddBranches::where('branch_id', $user->branch_id)->first();

        return view('Branch.profile', compact('user', 'branch'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $branch = AddBranches::where('branch_id', $user->branch_id)->firstOrFail();

        $request->validate([
            'branch_name' => 'required|string|max:255',
            'otp_email' => 'nullable|email',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        if ($request->otp_email && $request->otp_email !== $user->otp_email) {
            $user->pending_otp_email = $request->otp_email;
            $user->save();
        }


        $branch->branch_name = $request->branch_name;


        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logos'), $filename);


            if ($branch->logo && file_exists(public_path('uploads/logos/' . $branch->logo))) {
                unlink(public_path('uploads/logos/' . $branch->logo));
            }

            $branch->logo = $filename;
        }

        $branch->save();

        return back()->with('success', 'Profile updated successfully. If OTP email was changed, it will show after admin approval.');
    }



    public function updateAddress(Request $request)
    {
        $user = Auth::user();
        $branch = AddBranches::where('branch_id', $user->branch_id)->firstOrFail();

        $request->validate([
            'city'        => 'required|string',
            'state'       => 'required|string',
            'postal_code' => 'required|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        $branch->update([
            'city'        => $request->city,
            'state'       => $request->state,
            'postal_code' => $request->postal_code,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
        ]);

        return back()->with('success', 'Address updated successfully.');
    }


    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ], [
            'new_password.confirmed' => 'New password and confirmation do not match.',
        ]);
        // Check old password
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Current password is incorrect.']);
        }

        // Generate and store OTP
        $otp = random_int(100000, 999999); // cryptographically secure
        session([
            'password_change_otp' => $otp,
            'new_password_temp' => bcrypt($request->new_password),
        ]);

        // Choose email for OTP
        $email = $user->otp_email ?? $user->email;

        // Send OTP email
        try {
            Mail::to($email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send OTP email. Please try again.']);
        }

        return redirect()->route('settings')->with([
            'otp_sent' => true,
            'success' => 'An OTP has been sent to your email: ' . $email,
        ]);
    }

    public function changepasswordOtp(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        if ($request->otp == session('password_change_otp')) {
            $user->password = session('new_password_temp');
            $user->save();

             session()->forget(['password_change_otp', 'new_password_temp']);

        // Log the user out
        Auth::logout();

        // Invalidate the session completely
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Password changed successfully. Please log in with your new password.');
    } else {
        return back()->withErrors(['otp' => 'Invalid OTP. Please try again.'])->with('otp_sent', true);
    }
}
}