<?php

namespace App\Http\Controllers\Branch;

use Illuminate\Contracts\Validation\Validator;
use Carbon\Carbon;
use App\Models\bill;
use App\Models\User;
use App\Models\order;
use App\Models\customer;
use App\Models\add_product;
use App\Models\add_service;
use App\Models\AddBranches;
use App\Models\appointment;
use App\Models\chair_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Hamcrest\Number\OrderingComparison;
use Illuminate\Support\Facades\Auth;
use App\Models\waiting_list;
use App\Models\emp_detail;
use App\Models\pending_bill;


class BranchController extends Controller
{

   public function getWaitingList()
{
    $user = Auth::user();
    $branchId = $user->branch_id;

    $waitingList = DB::table('waiting_lists')
        ->join('customers', 'waiting_lists.customer_id', '=', 'customers.id')
        ->select(
            'waiting_lists.id',
            'customers.name',
            'customers.mobile',
            'waiting_lists.chair_id',
            'waiting_lists.staff_name',
            'waiting_lists.service_name',
            'waiting_lists.created_at'
        )
        ->where('waiting_lists.waiting_status', 0)      // ✅ Must be 0
        ->where('waiting_lists.cancel_status', 0)       // ✅ Must be 0
        ->where('waiting_lists.branch_id', $branchId)
        ->orderBy('waiting_lists.created_at', 'asc')
        ->paginate(5);

    return response()->json($waitingList);
}



    public function getAppointmentByMobile($mobile, Request $request)
    {

        // Step 2: Find customer by mobile
        $customer = customer::where('mobile', $mobile)->first();

        if (!$customer) {
            echo "Customer not found.";
            die();
        }



        // Step 1: Get latest waiting_list record by customer_id
        $waiting = waiting_list::where('customer_id', $customer->id)
            ->orderBy('id', 'desc')
            ->first();

        if (!$waiting) {
            echo "No waiting record found.";
            die();
        }

        // Step 2: Prepare service array
        $serviceNames = $waiting->service_name
            ? array_map('trim', explode(',', $waiting->service_name))
            : [];

        $services = [];

        foreach ($serviceNames as $name) {
            $services[] = [
                'service_name'      => $name,
                'service_duration'  => $waiting->service_duration,
                'service_qnty' => $waiting->service_qnty,
                'service_price'     => $waiting->service_price,
            ];
        }

        // Step 3: Prepare product array (optional)
        $products = [];

        if ($waiting->product_name) {
            $products[] = [
                'product_name'      => $waiting->product_name,
                'product_price'     => $waiting->product_price,
                'product_qnty' => $waiting->product_qnty,
            ];
        }

        // Step 6: Return final JSON response
        return response()->json([
            'success' => true,
            'customer' => [
                'name' => $customer->name,
                'mobile' => $customer->mobile,
                'email' => $customer->email,
                'gender' => $customer->gender,
                'senior_citizen' => $customer->senior_citizen,
            ],
            'chair_id' => $waiting->chair_id,
            'staff_name' => $waiting->staff_name,
            'services' => $services,
            'products' => $products,

        ]);
    }

    public function cancelWaiting($id)
{
    $waiting = waiting_list::find($id);

    if (!$waiting) {
        return response()->json(['success' => false, 'message' => 'Not found.'], 404);
    }

    $waiting->cancel_status = 1;
    $waiting->save();

    return response()->json(['success' => true]);
}

    public function checkBillStatus($appointment_id)
    {
        $appointment = appointment::find($appointment_id);
        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }

        $bill = bill::where('appointment_id', $appointment_id)->first();

        if ($bill && $bill->status === 'Paid') {
            return response()->json(['paid' => true]);
        }

        return response()->json(['paid' => false]);
    }


    public function checkout(Request $request)
    {
        $user = Auth::user();
        $branchId = $user->branch_id;
        $chairId = $request->input('chairId');
        $mobile = $request->input('mobile');
        $total = $request->input('total', 0);
        $services = $request->input('services', []);
        $products = $request->input('products', []);

        // Validate inputs
        $validator = validator($request->all(), [
            'chairId' => 'required|string',
            'mobile' => 'required|string',
            'services' => 'array',
            'products' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        // Get Customer
        $customer = customer::where('mobile', $mobile)->first();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Customer not found']);
        }

        // Get Appointment
        $appointment = appointment::with('staff')
            ->where('customer_id', $customer->id)
            ->where('chair_id', $chairId)
            ->latest()
            ->first();

        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Appointment not found']);
        }

        $appointmentId = $appointment->id;
        $staffName = $appointment->staff->name ?? 'Unknown';

        // Insert services
        foreach ($services as $srv) {
            DB::table('pending_bills')->insert([
                'customer_id' => $customer->id,
                'mobile' => $mobile,
                'appointment_id' => $appointmentId,
                'staff_name' => $staffName,
                'chair_id' => $chairId,
                'branch_id' => $branchId,
                'service_name' => $srv['name'],
                'service_duration' => $srv['duration'],
                'service_qnty' => $srv['qnty'],
                'service_price' => $srv['price'],
                'total_amount' => $total,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert or update products
        if (!empty($products)) {
            foreach ($products as $prd) {
                $exist = DB::table('pending_bills')
                    ->where('appointment_id', $appointmentId)
                    ->where('mobile', $mobile)
                    ->whereNull('product_name')
                    ->first();

                $prod_arr = [
                    'product_name' => $prd['name'],
                    'product_price' => $prd['price'],
                    'product_qnty' => $prd['product_qnty'],
                    'total_amount' => $prd['price'] * $prd['product_qnty'],
                    'updated_at' => now()
                ];

                if ($exist) {
                    DB::table('pending_bills')
                        ->where('id', $exist->id)
                        ->update($prod_arr);
                } else {
                    DB::table('pending_bills')->insert(array_merge($prod_arr, [
                        'customer_id' => $customer->id,
                        'mobile' => $mobile,
                        'appointment_id' => $appointmentId,
                        'staff_name' => $staffName,
                        'chair_id' => $chairId,
                        'branch_id' => $branchId,
                        'service_name' => null,
                        'service_duration' => null,
                        'service_qnty' => 0,
                        'service_price' => null,
                        'status' => 'pending',
                        'created_at' => now()
                    ]));
                }
            }
        }

        // Mark chair as available
        $chair = chair_detail::where('chair_id', $chairId)->first();
        if ($chair) {
            $chair->status = 0;
            $chair->save();
        }

        // Update staff status (fix: search via user table first)
        $user = User::where('name', $staffName)->first();
        if ($user) {
            $staff = emp_detail::where('user_id', $user->id)->first();
            if ($staff) {
                $staff->status = 0;
                $staff->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Checkout complete',
            'total' => $total
        ]);
    }


public function pendingBills()
{
    try {
        $branchId = Auth::user()->branch_id;

        $bills = pending_bill::select(
                'appointment_id',
                'mobile',
                'staff_name',
                DB::raw('GROUP_CONCAT(service_name SEPARATOR ", ") as services'),
                DB::raw('GROUP_CONCAT(product_name SEPARATOR ", ") as products'),
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw('MAX(created_at) as created_at')
            )
            ->where('status', 'pending')
            ->where('branch_id', $branchId)
            ->groupBy('appointment_id', 'mobile', 'staff_name')  // include staff_name in groupBy
            ->orderByDesc('created_at')
            ->get();

        return response()->json($bills);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Unable to fetch pending bills.'], 500);
    }
}


public function getpendingbilldata(Request $request, $mobile, $appointmentId)
{
    try {
        // Step 1: Retrieve pending bill using both mobile & appointment ID
        $pendingBill = pending_bill::with('customer')
            ->where('appointment_id', $appointmentId)
            ->where('status', 'pending')
            ->whereHas('customer', function ($query) use ($mobile) {
                $query->where('mobile', $mobile);
            })
            ->first(); // removed ->latest()

        // Step 2: Return error if no bill found
        if (!$pendingBill) {
            return response()->json([
                'success' => false,
                'message' => 'Pending bill not found for this mobile number and appointment.'
            ]);
        }

        // Step 3: Get all entries (rows) with same appointment ID and status
        $allEntries = pending_bill::where('appointment_id', $appointmentId)
            ->where('status', 'pending')
            ->get();

        // Step 4: Split into services and products
        $services = $allEntries->filter(fn($item) => !is_null($item->service_name))->values();
        $products = $allEntries->filter(fn($item) => !is_null($item->product_name))->values();

        // Step 5: Return response
        return response()->json([
            'success' => true,
            'customer' => $pendingBill->customer,
            'services' => $services,
            'products' => $products,
            'chair_id' => $pendingBill->chair_id,
            'staff_name' => $pendingBill->staff_name,
            'appointment_id' => $appointmentId,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching pending bill data.',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
