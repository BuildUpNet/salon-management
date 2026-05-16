@extends('AdminReports.layouts.main')

@section('title', 'Date Wise Report')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
@endsection

@section('content')
<div class="container mt-4 flex-grow-1">
    <h3 class="mb-4">
        <i class="bi bi-calendar-check"></i> Date Wise Report
    </h3>

    <!-- Filter Form -->
  <form method="POST" action="{{ route('adminreports.date') }}" class="row g-3 mb-4 align-items-end">

@csrf
      
        <div class="col-md-2">
            <label for="fromDate" class="form-label">From</label>
            <input type="date" class="form-control" name="from_date" id="fromDate" required value="{{ old('from_date') }}" />
        </div>
        <div class="col-md-2">
            <label for="toDate" class="form-label">To</label>
            <input type="date" class="form-control" name="to_date" id="toDate" required value="{{ old('to_date') }}" />
        </div>
        <div class="col-md-3">
            <label for="reportType" class="form-label">Report Type</label>
            <select class="form-select" name="report_type" id="reportType" required>
                <option value="">-- Select Type --</option>
                {{-- <option value="chair" {{ old('report_type') == 'chair' ? 'selected' : '' }}>Chair Wise</option> --}}
                <option value="service" {{ old('report_type') == 'service' ? 'selected' : '' }}>Service Wise</option>
<option value="product" {{ old('report_type') == 'product' ? 'selected' : '' }}>Product Wise</option>
<option value="cash" {{ old('report_type') == 'cash' ? 'selected' : '' }}>Cash Wise</option>

            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-warning w-100">Filter</button>
        </div>
    </form>

   <!-- Table Wrapper -->
<div class="table-responsive">
    <table class="table table-bordered table-hover text-center" style="table-layout: fixed; width: 85%;">
        <thead class="table-dark">
            <tr>
                <th style="width: 5%;">S.No.</th>
                <th style="width: 15%;">Date</th>
                <th style="width: 20%;">Customer</th>
                <th style="width: 15%;">Chair</th>
                <th style="width: 25%;">Service/Product</th>
                <th style="width: 10%;">Amount (₹)</th>
                <th style="width: 10%;">Payment Type</th>
            </tr>
        </thead>
        <tbody>
            @if(request()->filled('from_date') || request()->filled('to_date') || request()->filled('report_type'))
                @forelse ($reports as $index => $report)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($report->date)->format('d/m/Y') }}</td>
                        <td>{{ $report->mobile ?? '-' }}</td>
                        <td>{{ $report->chair_id ?? '-' }}</td>
                        <td>{{ $report->item_name ?? '-' }}</td>
                        <td>${{ number_format($report->amount, 2) }}</td>
                        <td>{{ ucfirst($report->payment_type) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No records found for selected filters.</td>
                    </tr>
                @endforelse

                @if($reports->count())
                    <tr class="fw-bold table-dark">
                        <td colspan="5" class="text-end">Total Amount</td>
                        <td colspan="2">₹{{ number_format($reports->sum('amount'), 2) }}</td>
                    </tr>
                @endif
            @else
                <tr>
                    <td colspan="7" class="text-center">Please apply filters to view report data.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/final_main.js') }}"></script>
@endsection
