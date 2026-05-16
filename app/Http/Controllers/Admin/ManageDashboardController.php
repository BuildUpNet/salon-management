<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\add_service;
use App\Models\User;
use App\Models\emp_detail;
use App\Models\AddBranches;

class ManageDashboardController extends Controller
{

  public function create_staff(Request $request)
{
    $query = User::where('role', 'emp')->with('empDetail');


    if ($request->has('search') && $request->search !== '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('mobile', 'like', "%$search%")
              ->orWhere('role_type', 'like', "%$search%");
        });
    }

    $staffs = $query->paginate(10)->appends(['search' => $request->search]);
    $branches = AddBranches::all();

    return view('Admin.manage-staff', compact('staffs', 'branches'));
}

   public function store_staff(Request $request)
{
    $request->validate([
        'name'       => 'required|string|max:255',
        'role_type'  => 'required|in:Manager,Receptionist,Stylist,Assistant',
        'branch_id'  => 'required|exists:add_branches,branch_id',
        'mobile'     => 'required|string|max:10|unique:users,mobile',
        'email'      => 'required|email|unique:users,email',
        'password'   => 'required|string|min:6',
    ]);

    // Auto-generate employee ID
    $lastEmp = emp_detail::orderBy('id', 'desc')->first();
    $nextNumber = $lastEmp ? $lastEmp->id + 1 : 1;
    $employee_id = 'EMP-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

    // Insert into users table
    $user = new User();
    $user->name = $request->name;
    $user->mobile = $request->mobile;
    $user->email = $request->email;
    $user->role = 'emp';
    $user->role_type = $request->role_type;
    $user->branch_id = $request->branch_id;
    $user->password = Hash::make($request->password);
    $user->status = 1;
    $user->save();

    // Insert into emp_details table
    $empDetail = new emp_detail();
    $empDetail->user_id = $user->id;
    $empDetail->employee_id = $employee_id;
    $empDetail->phone = $request->mobile; // You can rename if needed
    $empDetail->save();

    return redirect()->route('admin.create-staff')
        ->with('success', 'Staff member added successfully with ID: ' . $employee_id);
}

    public function update_staff(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255',
            'role_type'  => 'required|in:Manager,Receptionist,Stylist,Assistant',
            'mobile'     => 'required|string|max:10|unique:users,mobile,' . $user->id,
            'email'      => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Update user
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->role_type = $request->role_type;
        $user->save();

        // Update emp_detail
        $empDetail = emp_detail::where('user_id', $user->id)->first();
        if ($empDetail) {
            $empDetail->phone = $request->mobile;
            $empDetail->save();
        }

        return redirect()->route('admin.create-staff')->with('success', 'Staff updated successfully!');
    }


    public function delete_staff($id)
    {
        $user = user::findOrFail($id);

        // Delete related emp_detail record
        emp_detail::where('user_id', $user->id)->delete();

        // Delete user
        $user->delete();

        return redirect()->route('admin.create-staff')->with('success', 'Staff member deleted successfully!');
    }

   public function manage_services(Request $request)
{
    $query = add_service::query();

    if ($request->has('search') && $request->search !== null) {
        $searchTerm = $request->input('search');
        $query->where('service_name', 'like', "%{$searchTerm}%")
              ->orWhere('service_id', 'like', "%{$searchTerm}%")
              ->orWhere('gender', 'like', "%{$searchTerm}%");
    }

    $services = $query->paginate(10)->appends(['search' => $request->search]);

    return view('Admin.manage-service', compact('services'));
}

    public function add_service(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'gender' => 'required',
            'duration' => 'required',
            'price' => 'required|numeric',
        ]);


        $lastService = add_service::orderBy('id', 'desc')->first();
        $nextId = $lastService ? ((int)substr($lastService->service_id, 4)) + 1 : 1;
        $serviceId = 'sev-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);


        add_service::create([
            'service_id' => $serviceId,
            'service_name' => $request->service_name,
            'gender' => $request->gender,
            'duration' => $request->duration,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Service added successfully!');
    }
    public function update_service(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required',
            'gender' => 'required',
            'duration' => 'required',
            'price' => 'required|numeric',
        ]);

        $service = add_service::findOrFail($id);
        $service->update([
            'service_name' => $request->service_name,
            'gender' => $request->gender,
            'duration' => $request->duration,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.manage-service')->with('success', 'Service updated successfully!');
    }

    public function delete_service($id)
    {
        $service = add_service::findOrFail($id);
        $service->delete();

        return redirect()->back()->with('success', 'Service deleted successfully!');
    }
}
