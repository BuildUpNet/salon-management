@extends('Branch.layouts.main')

@section('title', 'Chair Wise Report')

@section('content')
<style>
.pagination {
    display: flex;
    justify-content: flex-end !important;
    margin-top: 0.4rem;
    gap: 0.3rem;
}
.pagination .page-item .page-link {
    color: #000;
    border: 1px solid #ddd;
    padding: 6px 12px;
    border-radius: 4px;
}
.pagination .page-item.active .page-link {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}
.pagination .page-link:hover {
    background-color: #ffe58f;
    border-color: #ffc107;
}
.pagination-info {
    display: none;
    font-size: 14px;
    color: #555;
}
</style>

<div class="container mt-3">
    <h3>
        <img src="https://img.icons8.com/ios-filled/64/0000/barber-chair.png" 
             alt="Chair Icon" width="48" height="48" style="margin-bottom: 8px;" />
        Chair Wise Report
    </h3>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('reports.chair') }}" class="row g-2  mb-2">
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
                    <option value="{{ $chair->chair_id }}" 
                        {{ request('chair') == $chair->chair_id ? 'selected' : '' }}>
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
        <table class="table table-bordered table-hover text-center" style="table-layout: auto; width: 85%;">
            <thead class="table-dark">
                <tr>
                    <th style="width: 5%;">S.No.</th>
                    <th style="width: 15%;">Date</th>
                    <th style="width: 20%;">Mobile</th>
                    <th style="width: 20%;">Chair</th>
                    <th style="width: 20%;">Payment Mode</th>
                    <th style="width: 20%;">Amount ($)</th>
                </tr>
            </thead>
            <tbody>
                @if(request()->filled('fromDate') || request()->filled('toDate') || request()->filled('chair') || request()->filled('paymentMode'))
                    @forelse($reports as $index => $report)
                        <tr>
                            <td>{{ $reports->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($report->date)->format('d/m/Y') }}</td>
                            <td style="word-wrap: break-word;">{{ $report->mobile }}</td>
                            <td style="word-wrap: break-word;">{{ $report->chair_code }}</td>
                            <td style="word-wrap: break-word;">{{ ucfirst($report->payment_type) }}</td>
                            <td style="word-wrap: break-word;">${{ number_format($report->final_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No records found.</td>
                        </tr>
                    @endforelse

                    @if($reports->count())
                        <tr class="fw-bold table-dark">
                            <td colspan="5" class="text-end">Grand Total (All Records)</td>
                            <td>${{ number_format($totalAmount, 2) }}</td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="6" class="text-center">Please apply filters to view report data.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if(request()->filled('fromDate') || request()->filled('toDate') || request()->filled('chair') || request()->filled('paymentMode'))
            <div class="d-flex flex-column align-items-center mt-1">
              
                <div>
                    {{ $reports->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/final_main.js') }}"></script>
@endsection
