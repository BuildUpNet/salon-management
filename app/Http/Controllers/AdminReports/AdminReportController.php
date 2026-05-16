<?php

namespace App\Http\Controllers\AdminReports;

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


class AdminReportController extends Controller
{
    public function allreport()
    {
        $user = Auth::user();
        return view('AdminReports.allreport');
    }


    public function dateWise(Request $request)
    {
        
        $totalAmount = 0;
        $reports = collect();

        if ($request->isMethod('post')) {
            $request->validate([
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date',
                'report_type' => 'required|string|in:service,product,cash'
            ]);

            $query = DB::table('appointments')
                ->join('bills', 'appointments.id', '=', 'bills.appointment_id')
                ->leftJoin('orders', 'appointments.id', '=', 'orders.appointment_id')
                ->leftJoin('chair_details', 'appointments.chair_id', '=', 'chair_details.chair_id')
                ->leftJoin('customers', 'appointments.mobile', '=', 'customers.mobile')
                ->whereBetween('appointments.date', [$request->from_date, $request->to_date])
                ->select(
                    'appointments.date',
                    'appointments.mobile as mobile',
                    'chair_details.chair_id',
                    'orders.service_name',
                    'orders.product_name',
                    'orders.service_price',
                    'orders.product_price',
                    'bills.final_amount as amount',
                    'bills.payment_type'
                );

            if ($request->report_type === 'service') {
                $query->whereNotNull('orders.service_name');
            } elseif ($request->report_type === 'product') {
                $query->whereNotNull('orders.product_name');
            }

            $reports = $query->get()->map(function ($row) use ($request) {
                $item = $request->report_type === 'service'
                    ? $row->service_name
                    : ($request->report_type === 'product'
                        ? $row->product_name
                        : ($row->service_name ?? $row->product_name));

                $amount = $request->report_type === 'service'
                    ? $row->service_price
                    : ($request->report_type === 'product'
                        ? $row->product_price
                        : $row->amount);

                return (object)[
                    'date' => $row->date,
                    'mobile' => $row->mobile ?? '-',
                    'chair_id' => $row->chair_id ?? '-',
                    'item_name' => $item ?? '-',
                    'amount' => $amount ?? 0,
                    'payment_type' => $row->payment_type ?? '-'
                ];
            });

            $totalAmount = $reports->sum('amount');
        }

        return view('AdminReports.datewise-report', compact('reports', 'totalAmount'));
    }

public function chairWise(Request $request)
{
    $user = Auth::user();
    $isAdmin = $user->type === 'admin'; // Change 'type' if needed

    // For admin: allow branch selection, else use user's own branch
    $selectedBranchId = $isAdmin
        ? $request->branch_id // from form
        : $user->branch_id;

    // Get chairs for selected branch or all if no filter applied (for admin only)
    $chairs = chair_detail::when($selectedBranchId, function ($query) use ($selectedBranchId) {
        return $query->where('branch_id', $selectedBranchId);
    })->get();

    // Start query
    $query = DB::table('bills')
        ->join('appointments', 'bills.appointment_id', '=', 'appointments.id')
        ->join('chair_details', 'appointments.chair_id', '=', 'chair_details.chair_id')
        ->select(
            'bills.final_amount',
            'bills.payment_type',
            'appointments.date',
            'appointments.mobile',
            'appointments.chair_id',
            'chair_details.chair_id as chair_code',
            'chair_details.branch_id'
        );

    // Apply branch filter for non-admin or selected branch for admin
    if ($selectedBranchId) {
        $query->where('chair_details.branch_id', $selectedBranchId);
    }

    // Date filters
    if ($request->filled('fromDate')) {
        $query->whereDate('appointments.date', '>=', $request->fromDate);
    }

    if ($request->filled('toDate')) {
        $query->whereDate('appointments.date', '<=', $request->toDate);
    }

    // Chair filter
    if ($request->filled('chair')) {
        $query->where('appointments.chair_id', $request->chair);
    }

    // Payment Mode filter
    if ($request->filled('paymentMode')) {
        $query->where('bills.payment_type', strtolower($request->paymentMode));
    }

    $reports = $query->get();

    // For admin dropdown
    $branches = $isAdmin ? AddBranches::all() : null;

    return view('AdminReports.chairwise-report', compact('reports', 'chairs', 'branches'));
}
public function serviceReport(Request $request)
{
    // Get all distinct services for the filter dropdown
    $services = DB::table('add_services')
        ->select('service_name')
        ->distinct()
        ->get();

    // Build the query
    $query = DB::table('orders')
        ->join('appointments', 'orders.appointment_id', '=', 'appointments.id')
        ->join('bills', 'appointments.id', '=', 'bills.appointment_id')
        ->join('chair_details', 'appointments.chair_id', '=', 'chair_details.chair_id')
        ->join('add_branches', 'chair_details.branch_id', '=', 'add_branches.branch_id') // ✅ Join branches
        ->select(
            'orders.service_name',
            'orders.service_duration',
            'orders.service_qnty',
            'orders.service_price',
            'appointments.date',
            'appointments.mobile',
            'bills.payment_type',
            'bills.final_amount',
            'add_branches.branch_id' // ✅ Include branch name
        )
        ->whereNotNull('orders.service_name');

    // ✅ Apply filters if they are set
    if ($request->filled('fromDate')) {
        $query->whereDate('appointments.date', '>=', $request->fromDate);
    }

    if ($request->filled('toDate')) {
        $query->whereDate('appointments.date', '<=', $request->toDate);
    }

    if ($request->filled('service')) {
        $query->where('orders.service_name', $request->service);
    }

    if ($request->filled('paymentMode')) {
        $query->where('bills.payment_type', strtolower($request->paymentMode));
    }

    $records = $query->get();

    return view('AdminReports.servicewise-report', compact('services', 'records'));
}

   public function productReport(Request $request)
{
    $products = DB::table('add_products')
        ->select('product_name')
        ->distinct()
        ->get();

    $query = DB::table('orders')
        ->join('appointments', 'orders.appointment_id', '=', 'appointments.id')
        ->join('bills', 'appointments.id', '=', 'bills.appointment_id')
        ->join('add_branches', 'appointments.branch_id', '=', 'add_branches.branch_id') // ✅ Join branches for branch_name
        ->select(
            'orders.product_name',
            'orders.product_price',
            'appointments.date',
            'appointments.mobile',
            'appointments.branch_id',
            'add_branches.branch_id' ,
            'bills.payment_type'
        )
        ->whereNotNull('orders.product_name');

    if ($request->filled('fromDate')) {
        $query->whereDate('appointments.date', '>=', $request->fromDate);
    }
    if ($request->filled('toDate')) {
        $query->whereDate('appointments.date', '<=', $request->toDate);
    }
    if ($request->filled('product')) {
        $query->where('orders.product_name', $request->product);
    }
    if ($request->filled('paymentMode')) {
        $query->where('bills.payment_type', strtolower($request->paymentMode));
    }

    $records = $query->get();

    return view('AdminReports.productwise-report', compact('products', 'records'));
}



public function BranchReport(Request $request)
{
    $branches = AddBranches::all();

    $query = AddBranches::query()
        ->select('add_branches.id', 'add_branches.branch_id')
        ->withCount([
            'emp_details',
            'chair_details',
            'bills as paid_bill_count' => function ($q) use ($request) {
                $q->where('status', 'paid');

                // Apply date filter if present
                if ($request->filled('fromDate') && $request->filled('toDate')) {
                    $q->whereBetween('created_at', [$request->fromDate, $request->toDate]);
                }
            },
        ]);

    // Filter by branch_id if selected
    if ($request->filled('branch_id')) {
        $query->where('add_branches.id', $request->branch_id);
    }

    $branchData = $query->get()->map(function ($branch) {
        return (object)[
            'branch_id'        => $branch->branch_id,
            'emp_detail'       => $branch->emp_details_count,
            'chair_detail'     => $branch->chair_details_count,
            'completed_orders' => $branch->paid_bill_count,
        ];
    });

    return view('AdminReports.branchwise-report', compact('branchData', 'branches'));
}


}
