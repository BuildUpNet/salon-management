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
use App\Models\emp_detail;
use Hamcrest\Number\OrderingComparison;
use Illuminate\Support\Facades\Auth;
use App\Models\waiting_list;
use App\Models\pending_bill;

class BranchDashboardController extends Controller
{

    public function branch_dashboard()
    {
        $user = Auth::user();
        $branchId = $user->branch_id;

        $staffs = User::with('empDetail')
            ->where('branch_id', $branchId)
            ->where('role', 'emp')
            ->get();

        $chairs = chair_detail::where('branch_id', $branchId)->get();

        // // Auto release chair after 60 minutes
        // foreach ($chairs as $chair) {
        //     if ($chair->status == 1 && $chair->booked_at) {
        //         if (Carbon::now()->diffInMinutes($chair->booked_at) >= 60) {
        //             $chair->status = 0;
        //             $chair->booked_at = null;
        //             $chair->save();
        //         }
        //     }
        // }

        $waitingCount = DB::table('waiting_lists')
            ->where('waiting_status', 0)
            ->where('branch_id', $branchId)
            ->count();

        $availableCount = chair_detail::where('branch_id', $branchId)
            ->where('status', 0)
            ->count();

        $bookedCount = chair_detail::where('branch_id', $branchId)
            ->where('status', 1)
            ->count();

        $pendingCount = DB::table('pending_bills')
            ->where('status', 'pending')
            ->where('branch_id', $branchId)
            ->count();

        $totalBillCount = DB::table('bills')
            ->where('branch_id', $branchId)
            ->count();

        // ✅ NEW: Load today's appointments for this branch
        $appointments = appointment::with('customer', 'staff')
            ->where('branch_id', $branchId)
            ->whereDate('date', now())
            ->where('status', '!=', 'cancelled') // Optional
            ->latest()
            ->get();

        return view('Branch.appointment', compact(
            'chairs',
            'staffs',
            'waitingCount',
            'totalBillCount',
            'availableCount',
            'pendingCount',
            'bookedCount',
            'appointments' // ✅ Pass this to view
        ));
    }

    public function chair_status()
    {
        $user = Auth::user();
        $branch = AddBranches::where('branch_id', $user->branch_id)->first();

        if (!$branch) {
            return back()->with('error', 'Branch not found.');
        }

        $chairs = chair_detail::where('branch_id', $branch->id)->get();
        return view('branch.chairs-status', compact('chairs'));
    }

    public function getServices()
    {
        $services = add_service::all();
        return response()->json($services);
    }

    public function getProducts()
    {
        $products = add_product::all();
        return response()->json($products);
    }

    public function getCustomerDetails($mobile)
    {
        $customer = customer::where('mobile', $mobile)->first();

        if ($customer) {
            return response()->json([
                'status' => true,
                'data' => [
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'gender' => $customer->gender,
                    'senior_citizen' => $customer->senior_citizen,
                ]
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Customer not found',
        ]);
    }

    public function bookChair(Request $request)
    {
        $chair = chair_detail::where('chair_id', $request->chair_id)->first();

        if ($chair) {
            $chair->status = 1; // mark as booked
            $chair->booked_at = Carbon::now();
            $chair->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Chair not found']);
    }

    public function updateStatus(Request $request, $chairId)
    {
        $chair = chair_detail::where('chair_id', $chairId)->first();

        if (!$chair) {
            return response()->json(['success' => false, 'message' => 'Chair not found']);
        }

        $chair->status = $request->status;
        $chair->save();

        return response()->json(['success' => true, 'message' => 'Chair status updated']);
    }

    public function bookAppointment(Request $request)
    {
  
        $user = Auth::user();
        $branch = AddBranches::where('branch_id', $user->branch_id)->first();
        $chairnotavailable =  $staffnotavailable =   $insertedServices = $insertedProducts = false;

        // Step 1: Validate input
        $validator = validator($request->all(), [
            'mobile' => 'required|digits:10',
            'services' => 'required|array|min:1',
            'products' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

       $customer = customer::updateOrCreate(
    [
        'mobile' => $request->mobile,
        'name' => $request->name,
        'email' => $request->email ?? null,
        'gender' => $request->gender ?? null,
        'senior_citizen' => $request->senior_citizen ?? null,
        'branch_id' => $branch->branch_id,
    ]
);



        $staffUser = User::where('name', $request->staff)->first();
        if (!$staffUser) {
            return response()->json(['success' => false, 'message' => 'Staff not found.']);
        }


        $staffStatus = DB::table('emp_details')->where('user_id', $staffUser->id)->value('status');
        // Step 3: Get chair
        $chair = chair_detail::where('chair_id', $request->chair)->first();
        if (!$chair) {
            return response()->json(['success' => false, 'message' => 'Chair not found']);
        }
        $staffStatusBusy = ($staffStatus == 1);
        $chairStatusBusy = ($chair->status == 1);

        if ($staffStatusBusy || $chairStatusBusy) {
            $existingWaiting = DB::table('waiting_lists')
                ->where('customer_id', $customer->id)
                ->whereDate('created_at', now()->toDateString())
                ->first();

            $waitingData = [
                'branch_id'         => $branch->branch_id,
                'customer_id'       => $customer->id,
                'chair_id'          => $request->chair,
                'staff_name'        => $request->staff,
                'service_name'      => implode(',', array_column($request->services ?? [], 'name')),
                'service_duration'  => $request->services[0]['duration'] ?? 0,
                'service_qnty'      => $request->services[0]['service_qnty'] ?? null,
                'service_price'     => $request->services[0]['price'] ?? 0,
                'product_name'      => $request->products[0]['name'] ?? null,
                'product_price'     => $request->products[0]['price'] ?? null,
                'product_qnty'      => $request->products[0]['product_qnty'] ?? null,
                'status'            => 'waiting',
                'waiting_status'    => 0,
                'updated_at'        => now(),
            ];

            if ($existingWaiting) {
                DB::table('waiting_lists')
                    ->where('id', $existingWaiting->id)
                    ->update($waitingData);
            } else {
                $waitingData['created_at'] = now();
                DB::table('waiting_lists')->insert($waitingData);
            }

            // Set flags
            $staffnotavailable = $staffStatusBusy;
            $chairnotavailable = $chairStatusBusy;

            return response()->json([
                'success' => true,
                'waiting' => true,
                'staffavailablestatus' => $staffnotavailable,
                'chairavailablestatus' => $chairnotavailable,
                'message' => $existingWaiting
                    ? 'Waiting list updated due to unavailability.'
                    : 'Added to waiting list due to unavailability.'
            ]);
        }


        // Step 4: Check if appointment exists for same mobile and date
        $appointment = null;

        if ($request->has('appointment_id') && !empty($request->appointment_id)) {
            $appointment = appointment::find($request->appointment_id);

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid appointment ID.'
                ]);
            }

            // Delete any pending bill
            DB::table('pending_bills')
                ->where('appointment_id', $request->appointment_id)
                ->delete();
        } else {
            $existingAppointment = appointment::where('mobile', $request->mobile)
                ->where('date', now()->toDateString())
                ->first();

            if ($existingAppointment) {
                $appointment = $existingAppointment;

                // Optionally update chair/staff/time
                $appointment->update([
                    'chair_id' => $request->chair,
                    'staff_id' => $staffUser->id,
                    'time_in' => now()->toTimeString(),
                ]);
            } else {
                $appointment = appointment::create([
                    'branch_id' => $branch->branch_id,
                    'customer_id' => $customer->id,
                    'mobile' => $request->mobile,
                    'date' => now()->toDateString(),
                    'chair_id' => $request->chair,
                    'staff_id' => $staffUser->id,
                    'time_in' => now()->toTimeString(),
                    'time_out' => null,
                    'status' => 'booked',
                ]);
            }
        }

        $totalDuration = 0;

        // Step 8: Insert services
        if (!empty($request['services'])) {
            foreach ($request['services'] as $srv) {
                $totalDuration += (int)$srv['duration'];
                $existingService = DB::table('orders')->where([
                    'appointment_id' => $appointment->id,
                    'service_name' => $srv['name']
                ])->first();
                $srv_arr = [
                    'branch_id'         => $branch->branch_id,
                    'mobile'           => $request->mobile,
                    'appointment_id'   => $appointment->id,
                    'service_name'     => $srv['name'],
                    'service_duration' => $srv['duration'],
                    'service_qnty'     => $srv['service_qnty'],
                    'service_price'    => $srv['price'],
                    'product_name'     => null,
                    'product_price'    => null,
                    'product_qnty'     => null,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];

                if ($existingService) {
                    DB::table('orders')->where('id', $existingService->id)->update($srv_arr);
                } else {
                    $srv_arr['created_at'] = now();
                    DB::table('orders')->insert($srv_arr);
                }

                $insertedServices = true;
            }
        }

        // Step 9: Insert/update products
        if (!empty($request['products'])) {
            foreach ($request['products'] as $prd) {
                $existingProduct = DB::table('orders')->where([
                    'appointment_id' => $appointment->id,
                    'product_name' => $prd['name']
                ])->first();
                $prod_arr = [
                    'branch_id'         => $branch->branch_id,
                    'product_name'   => $prd['name'],
                    'product_price'  => $prd['price'],
                    'product_qnty'   => $prd['product_qnty'],
                    'updated_at'     => now()
                ];

                if ($existingProduct) {
                    DB::table('orders')->where('id', $existingProduct->id)->update($prod_arr);
                } else {
                    $prod_arr = array_merge($prod_arr, [
                        'mobile'           => $request->mobile,
                        'appointment_id'   => $appointment->id,
                        'service_name'     => null,
                        'service_duration' => null,
                        'service_qnty'     => 0,
                        'service_price'    => null,
                        'created_at'       => now(),
                    ]);
                    DB::table('orders')->insert($prod_arr);
                }

                $insertedProducts = true;
            }
        }


        $chair->update(['status' => 1]);
        DB::table('emp_details')->where('user_id', $staffUser->id)->update(['status' => 1]);
        DB::table('waiting_lists')
            ->where('customer_id', $customer->id)
            ->where('waiting_status', 0)
            ->update(['waiting_status' => 1]);



        // Step 11: Return success
        return response()->json([
            'success' => true,
            'customer' => $customer,
            'appointment' => $appointment,
            'services' => $insertedServices,
            'products' => $insertedProducts,
            'total_duration' => $totalDuration,
            'waiting' => false,
            'staffavailablestatus' =>  $staffnotavailable,
            'chairavailablestatus' =>  $chairnotavailable,

        ]);
    }
    
  public function cancel($id)
{
    $appointment = Appointment::find($id);

    if (!$appointment) {
        return response()->json(['success' => false, 'message' => 'Appointment not found.'], 404);
    }

    if ($appointment->status !== 'booked') {
        return response()->json(['success' => false, 'message' => 'Only booked appointments can be cancelled.'], 400);
    }

    $appointment->status = 'cancelled';
    $appointment->time_out = now()->format('H:i:s');
    $appointment->save();

    // Free the chair
    chair_detail::where('chair_id', $appointment->chair_id)->update(['status' => 0]);

    // Free the staff (if assigned)
    if ($appointment->staff_id) {
        emp_detail::where('user_id', $appointment->staff_id)->update(['status' => 0]);
    }

    return response()->json(['success' => true, 'message' => 'Booking cancelled successfully.']);
}

    public function getChairAppointment($chair_id)
    {
        $appointment = appointment::where('chair_id', $chair_id)
            ->orderByDesc('id')
            ->first();

        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'No appointment found']);
        }

        $customer = $appointment->customer;
        $orders = order::where('appointment_id', $appointment->id)->get();

        // Get staff name (if staff_id exists)
        $staffName = '';
        if ($appointment->staff_id) {
            $staff = User::find($appointment->staff_id);
            $staffName = $staff ? $staff->name : '';
        }

        // Separate services and products
        $services = $orders->where('service_name', '!=', null)->values();
        $products = $orders->where('product_name', '!=', null)->values();

        return response()->json([
            'success' => true,
            'appointment_id' => $appointment->id,
            'appointment' => $appointment,
            'customer' => $customer,
            'services' => $services,
            'products' => $products,
            'staff_name' => $staffName, // <-- add this
        ]);
    }
    public function payBill(Request $request)
    {
        $user = Auth::user();
        $branch = AddBranches::where('branch_id', $user->branch_id)->first();

        $validator = validator($request->all(), [
            'mobile' => 'required|digits:10',
            'services' => 'array',
            'products' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        // ✅ Step 1: Create or update customer
        $customer = customer::updateOrCreate(
            ['mobile' => $request->mobile],
            [
                'name' => $request->name,
                'email' => $request->email ?? null,
                'gender' => $request->gender ?? null,
                'senior_citizen' => $request->senior_citizen ?? null,
                'branch_id' => $branch->branch_id,
            ]
        );

        $appointmentId = null;
        $appointment = null;

        // ✅ CASE 1: Only Products (no services)
        if (!empty($request->products) && empty($request->services)) {
            $staffUser = User::where('name', $request->staff)->first();

            $appointment = appointment::create([
                'customer_id' => $customer->id,
                'mobile' => $request->mobile,
                'date' => now()->toDateString(),
                'staff_id' => $staffUser->id ?? null,
                'time_in' => now()->toTimeString(),
                'branch_id' => $branch->branch_id,
            ]);

            $appointmentId = $appointment->id;

            foreach ($request->products as $product) {
                DB::table('orders')->insert([
                    'branch_id' => $branch->branch_id,
                    'mobile' => $request->mobile,
                    'appointment_id' => $appointmentId,
                    'product_name' => $product['name'],
                    'product_qnty' => $product['product_qnty'],
                    'product_price' => $product['price'],
                    'service_name' => null,
                    'service_duration' => null,
                    'service_qnty' => 0,
                    'service_price' => null,
                    'created_at' => now(),
                ]);
            }
        }

        // ✅ CASE 2: Pending Bill Exists
        elseif (!empty($request->services) || !empty($request->products)) {
            $pending = pending_bill::where('mobile', $request->mobile)
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($pending) {
                $appointmentId = $pending->appointment_id;

                $existingServiceNames = DB::table('orders')
                    ->where('appointment_id', $appointmentId)
                    ->whereNotNull('service_name')
                    ->pluck('service_name')
                    ->toArray();

                $incomingServiceNames = collect($request->services ?? [])->pluck('name')->toArray();

                $newServices = array_diff($incomingServiceNames, $existingServiceNames);

                if (!empty($newServices)) {
                    pending_bill::where('appointment_id', $appointmentId)
                        ->where('mobile', $request->mobile)
                        ->where('status', 'pending')
                        ->delete();

                    DB::table('orders')->where('appointment_id', $appointmentId)->delete();

                    return response()->json([
                        'success' => false,
                        'message' => 'You have added a new service. Please book again before proceeding to payment.',
                        'new_services' => $newServices,
                    ]);
                }

                // ✅ Insert services if not already in DB
                foreach ($request->services ?? [] as $service) {
                    $exists = DB::table('orders')
                        ->where('appointment_id', $appointmentId)
                        ->where('service_name', $service['name'])
                        ->exists();

                    if (!$exists) {
                        DB::table('orders')->insert([
                            'branch_id' => $branch->branch_id,
                            'mobile' => $request->mobile,
                            'appointment_id' => $appointmentId,
                            'service_name' => $service['name'],
                            'service_duration' => $service['duration'],
                            'service_qnty' => $service['service_qnty'],
                            'service_price' => $service['price'],
                            'product_name' => null,
                            'product_qnty' => null,
                            'product_price' => null,
                            'created_at' => now(),
                        ]);
                    }
                }

                // ✅ Insert new products
                foreach ($request->products ?? [] as $product) {
                    DB::table('orders')->insert([
                        'branch_id' => $branch->branch_id,
                        'mobile' => $request->mobile,
                        'appointment_id' => $appointmentId,
                        'product_name' => $product['name'],
                        'product_qnty' => $product['product_qnty'],
                        'product_price' => $product['price'],
                        'service_name' => null,
                        'service_duration' => null,
                        'service_qnty' => 0,
                        'service_price' => null,
                        'created_at' => now(),
                    ]);
                }

                // ✅ Mark bill as paid
                $updated = pending_bill::where('appointment_id', $appointmentId)
                    ->where('mobile', $request->mobile)
                    ->where('status', 'pending')
                    ->update(['status' => 'paid']);
            }

            // ✅ CASE 3: No pending bill, both services/products
            else {
                $appointment = appointment::where('chair_id', $request->chair_id)
                    ->where('mobile', $request->mobile)
                    ->latest()
                    ->first();

                if (!$appointment) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please book the appointment before proceeding with payment.'
                    ]);
                }

                $appointmentId = $appointment->id;

                // Clear old orders
                DB::table('orders')->where('appointment_id', $appointmentId)->delete();

                foreach ($request->services ?? [] as $service) {
                    DB::table('orders')->insert([
                        'branch_id' => $branch->branch_id,
                        'mobile' => $request->mobile,
                        'appointment_id' => $appointmentId,
                        'service_name' => $service['name'],
                        'service_duration' => $service['duration'],
                        'service_qnty' => $service['service_qnty'],
                        'service_price' => $service['price'],
                        'product_name' => null,
                        'product_qnty' => null,
                        'product_price' => null,
                        'created_at' => now(),
                    ]);
                }

                foreach ($request->products ?? [] as $product) {
                    DB::table('orders')->insert([
                        'branch_id' => $branch->branch_id,
                        'mobile' => $request->mobile,
                        'appointment_id' => $appointmentId,
                        'product_name' => $product['name'],
                        'product_qnty' => $product['product_qnty'],
                        'product_price' => $product['price'],
                        'service_name' => null,
                        'service_duration' => null,
                        'service_qnty' => 0,
                        'service_price' => null,
                        'created_at' => now(),
                    ]);
                }
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No services or products selected.'
            ]);
        }

        // ✅ Step 4: Generate Bill
        $latestBill = bill::orderBy('id', 'desc')->first();
        $newOrderId = $latestBill && $latestBill->order_id
            ? 'ORD-' . str_pad((int)substr($latestBill->order_id, 4) + 1, 4, '0', STR_PAD_LEFT)
            : 'ORD-0001';

        $bill = bill::create([
            'branch_id' => $branch->branch_id,
            'appointment_id' => $appointmentId,
            'order_id' => $newOrderId,
            'discount' => $request->bill['discount'],
            'total' => $request->bill['total'],
            'msf' => $request->bill['msf'],
            'final_amount' => $request->bill['final_amount'],
            'payment_type' => $request->bill['payment_type'] ?? 'Cash',
            'status' => 'Paid',
        ]);

        // ✅ Step 5: Free chair and staff
        if ($appointmentId) {
            $appointment = appointment::find($appointmentId);

            if ($appointment && $appointment->chair_id) {
                chair_detail::where('chair_id', $appointment->chair_id)->update(['status' => 0]);
            }

            if ($appointment && $appointment->staff_id) {
                $staff = emp_detail::where('user_id', $appointment->staff_id)->first();
                if ($staff) {
                    $staff->status = 0;
                    $staff->save();
                }
            }
        }



        return response()->json([
            'success' => true,
            'message' => 'Bill generated successfully.',
            'bill_id' => $bill->id,
            'order_id' => $newOrderId,
            'bill_data' => $bill,
            'statusupdate' => $updated ?? false,
        ]);
    }

    public function printBill($id)
    {
        $bill = bill::findOrFail($id);
        $orders = order::where('appointment_id', $bill->appointment_id)->get();

        return view('Branch.bill_receipt', compact('bill', 'orders'));
    }
}
