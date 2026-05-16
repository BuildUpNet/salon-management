<?php

namespace App\Http\Controllers\Report;

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
use App\Models\Attendance;
use Illuminate\Pagination\Paginator;

use Illuminate\Pagination\LengthAwarePaginator;


class BranchReportController extends Controller
{
    public function boot()
{
    Paginator::useBootstrapFive(); // For Bootstrap 5
}
    public function report()
    {
        $user = Auth::user();
        return view('Report.report');
    }
    
public function dateWise(Request $request)
{
    $branchId = auth()->user()->branch_id;

    // If no date filters are provided, return empty paginator
    if (!$request->filled('from_date') || !$request->filled('to_date')) {
        $empty = new LengthAwarePaginator([], 0, 10, 1);
        return view('Report.date-wise-report', [
            'reports' => $empty,
            'totalAmount' => 0
        ]);
    }

    // Validate date inputs
    $request->validate([
        'from_date' => 'required|date',
        'to_date'   => 'required|date|after_or_equal:from_date'
    ]);

    // Main query to get paginated report data
    $query = DB::table('bills')
        ->join('appointments', 'bills.appointment_id', '=', 'appointments.id')
        ->leftJoin('orders', 'appointments.id', '=', 'orders.appointment_id')
        ->where('appointments.branch_id', $branchId)
        ->whereBetween('appointments.date', [$request->from_date, $request->to_date])
        ->select(
            'appointments.date',
            'appointments.mobile',
            'appointments.chair_id',
            'bills.order_id as bill_no',
            'bills.final_amount as amount',
            'bills.payment_type',
            DB::raw("GROUP_CONCAT(DISTINCT orders.service_name SEPARATOR ', ') as services"),
            DB::raw("GROUP_CONCAT(DISTINCT orders.product_name SEPARATOR ', ') as products")
        )
        ->groupBy(
            'appointments.date',
            'appointments.mobile',
            'appointments.chair_id',
            'bills.order_id',
            'bills.final_amount',
            'bills.payment_type'
        )
        ->orderBy('bills.order_id', 'asc'); // Correct ordering

    // Paginated result
    $reports = $query->paginate(10)->appends($request->all());

    // Grand total amount without grouping
    $totalAmount = DB::table('bills')
        ->join('appointments', 'bills.appointment_id', '=', 'appointments.id')
        ->where('appointments.branch_id', $branchId)
        ->whereBetween('appointments.date', [$request->from_date, $request->to_date])
        ->sum('bills.final_amount');

    return view('Report.date-wise-report', compact('reports', 'totalAmount'));
}

public function chairWise(Request $request)
{
    $user = Auth::user();
    $branch = AddBranches::where('branch_id', $user->branch_id)->first();

    // Correct chair fetch
    $chairs = chair_detail::where('branch_id', $user->branch_id)->get();

    $baseQuery = DB::table('bills')
        ->join('appointments', 'bills.appointment_id', '=', 'appointments.id')
        ->join('chair_details', 'appointments.chair_id', '=', 'chair_details.chair_id')
        ->where('chair_details.branch_id', $user->branch_id)
        ->select(
            'bills.final_amount',
            'bills.payment_type',
            'appointments.date',
            'appointments.mobile',
            'appointments.chair_id',
            'chair_details.chair_id as chair_code'
        );

    if ($request->filled('fromDate')) {
        $baseQuery->whereDate('appointments.date', '>=', $request->fromDate);
    }
    if ($request->filled('toDate')) {
        $baseQuery->whereDate('appointments.date', '<=', $request->toDate);
    }
    if ($request->filled('chair')) {
        $baseQuery->where('appointments.chair_id', $request->chair);
    }
    if ($request->filled('paymentMode')) {
        $baseQuery->where('bills.payment_type', strtolower($request->paymentMode));
    }

    // Clone query for total calculation
    $totalAmount = (clone $baseQuery)->sum('bills.final_amount');

    // Paginated data
    $reports = $baseQuery->orderBy('appointments.date', 'desc')->paginate(10);

    return view('Report.chair-wise-report', compact('reports', 'chairs', 'totalAmount'));
}

public function serviceReport(Request $request)
{
    $user = Auth::user();

    // Get all distinct services for dropdown
    $services = DB::table('add_services')
        ->select('service_name')
        ->distinct()
        ->get();

    // Base query for service records
    $query = DB::table('orders')
        ->join('appointments', 'orders.appointment_id', '=', 'appointments.id')
        ->join('bills', 'appointments.id', '=', 'bills.appointment_id')
        ->where('appointments.branch_id', $user->branch_id)
        ->whereNotNull('orders.service_name')
        ->select(
            'orders.service_name',
            'orders.service_duration',
            'orders.service_qnty',
            'orders.service_price',
            'appointments.date',
            'appointments.mobile',
            'bills.payment_type',
            'bills.final_amount'
        );

    // Apply filters
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

    // Clone query to calculate total before pagination
    $totalAmount = (clone $query)->sum('orders.service_price');

    // Paginate main query
    $records = $query->orderBy('appointments.date', 'asc')
                     ->paginate(10)
                     ->appends($request->all());

    return view('Report.service-wise-report', compact('services', 'records', 'totalAmount'));
}

    public function productReport(Request $request)
    {
        $user = Auth::user();
        $products = DB::table('add_products')
            ->select('product_name')
            ->distinct()
            ->get();

        $query = DB::table('orders')
            ->join('appointments', 'orders.appointment_id', '=', 'appointments.id')
            ->join('bills', 'appointments.id', '=', 'bills.appointment_id')
            ->where('appointments.branch_id', $user->branch_id) // ✅
            ->select(
                'orders.product_name',
                'orders.product_price',
                'appointments.date',
                'appointments.mobile',
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


        return view('Report.product-wise-report', compact('products', 'records'));
    }

public function staffReport(Request $request)
{
    $branchId = auth()->user()->branch_id;

    $staffList = DB::table('attendances')
        ->where('branch_id', $branchId)
        ->pluck('staff_name', 'emp_id');

    $attendances = collect();
    $totalMinutes = 0;

    if ($request->filled(['fromDate', 'toDate']) || $request->filled('staff')) {
        $query = DB::table('attendances')->where('branch_id', $branchId);

        if ($request->filled('fromDate') && $request->filled('toDate')) {
            $query->whereBetween('date', [$request->fromDate, $request->toDate]);
        }

        if ($request->filled('staff')) {
            $query->where('emp_id', $request->staff);
        }

        // ---- Grand total calculation (before pagination)
        $totalQuery = clone $query;
        $allRecords = $totalQuery->get();

        foreach ($allRecords as $row) {
            if (!empty($row->hours)) {
                // Convert "HH:MM" to minutes
                $parts = explode(':', $row->hours);
                $h = isset($parts[0]) && is_numeric($parts[0]) ? (int) $parts[0] : 0;
                $m = isset($parts[1]) && is_numeric($parts[1]) ? (int) $parts[1] : 0;
                $totalMinutes += ($h * 60) + $m;
            }
        }

        // ---- Paginate results for display
        $attendances = $query->orderBy('date', 'asc')->paginate(10);
    }

    return view('Report.staff-wise-report', [
        'attendances' => $attendances,
        'reportdata' => $staffList,
        'totalHours' => floor($totalMinutes / 60) . 'h ' . ($totalMinutes % 60) . 'm', // grand total
    ]);
}


}
