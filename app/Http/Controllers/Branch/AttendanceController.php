<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\emp_detail;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function showAttendence(Request $request)
    {
        $branch_id = Auth::user()->branch_id;
        $date = $request->input('date', Carbon::today()->toDateString());

        $employees = User::join('emp_details', 'users.id', '=', 'emp_details.user_id')
            ->where('users.role', 'emp')
            ->where('users.branch_id', $branch_id)
            ->select('users.id', 'users.name', 'users.role_type', 'emp_details.employee_id')
            ->get();

        $attendances = Attendance::where('branch_id', $branch_id)
            ->where('date', $date)
            ->get()
            ->keyBy('emp_id');

        return view('Branch.attendence', compact('employees', 'attendances', 'date'));
    }

// Already perfect for multi-time check-in/check-out per day
public function checkIn($employee_id)
{
    $branch_id = Auth::user()->branch_id;
    $today = Carbon::today()->toDateString();

    $user = User::join('emp_details', 'users.id', '=', 'emp_details.user_id')
        ->where('emp_details.employee_id', $employee_id)
        ->where('users.branch_id', $branch_id)
        ->select('users.id', 'users.name', 'users.role', 'emp_details.employee_id')
        ->first();

    if (!$user) {
        return redirect()->back()->with('error', 'Employee not found.');
    }

    // Prevent double check-in if check-out not done
    $open = Attendance::where('emp_id', $employee_id)
        ->where('branch_id', $branch_id)
        ->where('date', $today)
        ->whereNull('check_out')
        ->first();

    if ($open) {
        return redirect()->back()->with('error', 'Already checked in. Please check out first.');
    }

    Attendance::create([
        'emp_id' => $employee_id,
        'branch_id' => $branch_id,
        'date' => $today,
        'staff_name' => $user->name,
        'role' => $user->role,
        'check_in' => now()->format('H:i:s'),
    ]);

    return redirect()->back()->with('success', 'Check-In recorded.');
}

public function checkOut($employee_id)
{
    $branch_id = Auth::user()->branch_id;
    $today = Carbon::today()->toDateString();

    $attendance = Attendance::where('emp_id', $employee_id)
        ->where('branch_id', $branch_id)
        ->where('date', $today)
        ->whereNotNull('check_in')
        ->whereNull('check_out')
        ->latest()
        ->first();

    if (!$attendance) {
        return redirect()->back()->with('error', 'Check-In not found. Please check-in first.');
    }

    $checkInTime = Carbon::parse($attendance->check_in);
    $checkOutTime = Carbon::now();

    // Calculate total duration in hours and minutes
    $diffInMinutes = $checkOutTime->diffInMinutes($checkInTime);
    $hours = floor($diffInMinutes / 60);
    $minutes = $diffInMinutes % 60;
    $workedTime = sprintf('%02d:%02d', $hours, $minutes); // e.g. 03:45

    $attendance->update([
        'check_out' => $checkOutTime->format('H:i:s'),
        'hours' => $workedTime,
    ]);

    return redirect()->back()->with('success', 'Check-Out recorded. Worked time: ' . $workedTime);
}

}
