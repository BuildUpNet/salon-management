<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\AddBranches;
use App\Models\chair_detail;
use App\Models\emp_detail;
use App\Models\pending_bill;
use App\Models\order;
use App\Models\bill;
use App\Models\waiting_list;
use App\Models\User;


class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $branchCount = AddBranches::count();
        $totalChairs = chair_detail::count();
        $availableChairs = chair_detail::where('status', 0)->count();
        $staffCount = emp_detail::count();
        $waitingClients = waiting_list::where('waiting_status', 0)->count();
        $pendingOrders = pending_bill::where('status', 'pending')->count();
        $totalOrders = order::select('mobile')->distinct()->count();
        $approvedOtpCount = User::whereNotNull('pending_otp_email')->count();
        $completedOrders = bill::count();

        return view('Admin.dashboard', compact(
            'branchCount',
            'totalChairs',
            'waitingClients',
            'availableChairs',
            'staffCount',
            'pendingOrders',
            'totalOrders',
            'approvedOtpCount',
            'completedOrders'

        ));
    }

    public function manage()
    {
        return view('Admin.manage');
    }

  public function create_branches(Request $request)
{
    $query = AddBranches::leftJoin('users', 'add_branches.branch_id', '=', 'users.branch_id')
        ->where('users.role', 'branch')
        ->select('add_branches.*', 'users.otp_email as otp_email');

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('add_branches.branch_name', 'like', "%$search%")
              ->orWhere('add_branches.state', 'like', "%$search%")
                 ->orWhere('add_branches.email', 'like', "%$search%");
        });
    }

    $branches = $query->paginate(10)->appends(['search' => $request->search]);

    return view('Admin.manage-branch', compact('branches'));
}

    public function add_branches(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'number_of_chairs' => 'required|integer|min:0',
            'password' => 'required|string|min:6',
            'otp_email' => 'required|email|unique:users,email',

        ]);

        // Generate auto-incrementing branch ID
        $lastBranch = AddBranches::orderBy('id', 'desc')->first();
        $nextId = $lastBranch ? $lastBranch->id + 1 : 1;
        $branchId = 'bra-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        // Generate default email using branchId
        $email = 'branch' . str_pad($nextId, 5, '0', STR_PAD_LEFT) . '@gmail.com';

        // Save branch
        $branch = AddBranches::create([
            'branch_name' => $request->branch_name,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'number_of_chairs' => $request->number_of_chairs,
            'branch_id' => $branchId,
            'email' => $email,
            'password' => Hash::make($request->password),
        ]);

        // Create user
        DB::table('users')->insert([
            'name' => $request->branch_name,
            'branch_id' => $branchId,
            'email' => $email,
            'otp_email' => $request->otp_email,
            'password' => Hash::make($request->password),
            'role' => 'branch',
            'status' => 1,
        ]);

        // Insert chairs
        for ($i = 1; $i <= $request->number_of_chairs; $i++) {
            $chairId = $branchId . '-ch-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            chair_detail::insert([
                'branch_id' => $branchId,
                'chair_id' => $chairId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Branch and chairs added successfully!');
    }


    public function edit_branch($id)
    {
        $branch = AddBranches::findOrFail($id);
        return view('admin.edit-branch', compact('branch'));
    }


    public function update_branch(Request $request, $id)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'number_of_chairs' => 'required|integer|min:0',

        ]);

        $branch = AddBranches::findOrFail($id);

        // Save the old chair count
        $oldChairCount = $branch->number_of_chairs;
        $newChairCount = $request->number_of_chairs;
        $branchId = $branch->branch_id;

        // Update basic branch info
        $branch->update([
            'branch_name' => $request->branch_name,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'number_of_chairs' => $newChairCount,
        ]);

        // Update name in users table too
        DB::table('users')
            ->where('branch_id', $branchId)
            ->update(['name' => $request->branch_name]);

        // Sync chairs
        if ($newChairCount < $oldChairCount) {
            // Delete extra chairs
            $chairsToDelete = $oldChairCount - $newChairCount;
            chair_detail::where('branch_id', $branch->id)
                ->orderBy('chair_id', 'desc')
                ->take($chairsToDelete)
                ->delete();
        } elseif ($newChairCount > $oldChairCount) {
            // Add new chairs
            for ($i = $oldChairCount + 1; $i <= $newChairCount; $i++) {
                $chairId = $branchId . '-ch-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                chair_detail::insert([
                    'branch_id' => $branch->branch_id,
                    'chair_id' => $chairId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back()->with('success', 'Branch and chairs updated successfully!');
    }


    public function delete_branch($id)
    {
        $branch = AddBranches::findOrFail($id);

        // Delete related users and chairs
        DB::table('users')->where('branch_id', $branch->branch_id)->delete();
        DB::table('chair_details')->where('branch_id', $branch->id)->delete();

        // Delete branch
        $branch->delete();

        return back()->with('success', 'Branch and its related data deleted successfully!');
    }
 public function getApprovedEmails()
{
    $emails = User::whereNotNull('pending_otp_email')
        ->get(['id', 'pending_otp_email as email', 'branch_id']);

    return response()->json($emails);
}

public function approveOtpEmail(Request $request)
{
    $user = User::where('branch_id', $request->branch_id)->first();

    if ($user && $user->pending_otp_email) {
        $user->otp_email = $user->pending_otp_email;
        $user->pending_otp_email = null;
        $user->save();

        return response()->json(['success' => true, 'message' => 'OTP email approved.']);
    }

    return response()->json(['success' => false, 'message' => 'User not found or no pending OTP email.']);
}


public function denyEmail($id)
{
    $user = User::find($id);

    if ($user && $user->pending_otp_email) {
        $user->pending_otp_email = null;
        $user->save();

        return response()->json(['message' => 'Email denied and removed.']);
    }

    return response()->json(['message' => 'User not found or no pending email.'], 404);
}
}
