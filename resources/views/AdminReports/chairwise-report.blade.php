@extends('AdminReports.layouts.main')

@section('title', 'Chair Wise Report')

@section('content')
<div class="container mt-4">
    <h3>
        <img src="{{ asset('assets/images/Icon/barber-chair-bold.png') }}" alt="Chair Icon">
        Chair Wise Report
    </h3>

    <!-- Filter Form -->
    <form method="POST" action="{{ route('adminreports.chair') }}" class="row g-2 mt-3 mb-4">
        @csrf
        @if(Auth::user()->user_type == 'admin')
            <div class="col-md-2">
                <label for="branch_id" class="form-label">Branch</label>
                <select id="branch_id" name="branch_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Branches</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-md-2">
            <label for="fromDate" class="form-label">From Date</label>
            <input type="date" id="fromDate" name="fromDate" class="form-control" value="{{ request('fromDate') }}" />
        </div>
        <div class="col-md-2">
            <label for="toDate" class="form-label">To Date</label>
            <input type="date" id="toDate" name="toDate" class="form-control" value="{{ request('toDate') }}" />
        </div>
        <div class="col-md-2">
            <label for="chair" class="form-label">Chair</label>
            <select id="chair" name="chair" class="form-select">
                <option value="">All Chairs</option>
                @foreach ($chairs as $chair)
                    <option value="{{ $chair->chair_id }}" {{ request('chair') == $chair->chair_id ? 'selected' : '' }}>
                        {{ $chair->chair_id }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="paymentMode" class="form-label">Payment Mode</label>
            <select id="paymentMode" name="paymentMode" class="form-select">
                <option value="">All</option>
                <option value="Cash" {{ request('paymentMode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="Card" {{ request('paymentMode') == 'Card' ? 'selected' : '' }}>Card</option>
                <option value="UPI" {{ request('paymentMode') == 'UPI' ? 'selected' : '' }}>UPI</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-warning w-100">Filter</button>
        </div>
    </form>

    <!-- Report Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center" style="table-layout: fixed; width: 87%;">
            <thead class="table-dark">
                <tr>
                    <th style="width: 5%;">S.No.</th>
                    <th style="width: 10%;">Date</th>
                    <th style="width: 15%;">Mobile</th>
                    <th style="width: 15%;">Branch</th>
                    <th style="width: 15%;">Chair</th>
                    <th style="width: 15%;">Payment Mode</th>
                    <th style="width: 15%;">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                @if(request()->anyFilled(['fromDate', 'toDate', 'chair', 'paymentMode', 'branch_id']))
                    @forelse($reports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($report->date)->format('d/m/Y') }}</td>
                            <td>{{ $report->mobile }}</td>
                             <td>{{ $report->branch_id }}</td> 
                            <td>{{ $report->chair_code }}</td>
                            <td>{{ ucfirst($report->payment_type) }}</td>
                            <td>₹{{ number_format($report->final_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No records found.</td>
                        </tr>
                    @endforelse

                    @if($reports->count())
                        <tr class="fw-bold table-dark">
                            <td colspan="6" class="text-end">Total Amount</td>
                            <td>₹{{ number_format($reports->sum('final_amount'), 2) }}</td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="6" class="text-center">Please apply filters to view report data.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/final_main.js') }}"></script>
@endsection
