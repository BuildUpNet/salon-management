@extends('Branch.layouts.main')

@section('title', 'Service Wise Report')

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
    font-size: 14px;
    color: #555;
}
</style>

<div class="container mt-1 flex-grow-1">
    <h3><i class="bi bi-person-fill-gear fs-2"></i> Service Wise Report</h3>

    <!-- Filter Form -->
    <form class="row g-3 mt-1 mb-4" method="GET" action="{{ route('reports.service') }}">
        <div class="col-md-2">
            <label for="fromDate" class="form-label">From Date</label>
            <input type="date" id="fromDate" name="fromDate" class="form-control" 
                   value="{{ request('fromDate') }}" />
        </div>
        <div class="col-md-2">
            <label for="toDate" class="form-label">To Date</label>
            <input type="date" id="toDate" name="toDate" class="form-control" 
                   value="{{ request('toDate') }}" />
        </div>
        <div class="col-md-2">
            <label for="service" class="form-label">Service</label>
            <select id="service" name="service" class="form-select">
                <option value="">All Services</option>
                @foreach ($services as $service)
                    <option value="{{ $service->service_name }}"
                        {{ request('service') == $service->service_name ? 'selected' : '' }}>
                        {{ $service->service_name }}
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
        <table class="table table-bordered table-hover text-center align-middle" style="table-layout: fixed; width: 85%;">
            <thead class="table-dark">
                <tr>
                    <th style="width: 5%;">S.No.</th>
                    <th style="width: 15%;">Date</th>
                    <th style="width: 15%;">Customer</th>
                    <th style="width: 15%;">Service</th>
                    <th style="width: 15%;">Payment Mode</th>
                    <th style="width: 15%;">Amount ($)</th>
                </tr>
            </thead>
            <tbody>
                @if(request()->filled('fromDate') || request()->filled('toDate') || request()->filled('service') || request()->filled('paymentMode'))
                    @forelse ($records as $index => $record)
                        <tr>
                            <td>{{ $records->firstItem() ? $records->firstItem() + $index : $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                            <td>{{ $record->mobile }}</td>
                            <td>{{ $record->service_name }}</td>
                            <td>{{ ucfirst($record->payment_type) }}</td>
                            <td>${{ number_format($record->service_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No records found.</td>
                        </tr>
                    @endforelse
                     @if($records->count())
                        <tr class="fw-bold table-dark">
                            <td colspan="5" class="text-end">Grand Total (All Records)</td>
                            <td>${{ number_format($totalAmount, 2) }}</td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Please apply a filter to view Service-wise report data.
                        </td>
                    </tr>
                @endif
            </tbody>

        </table>

        {{-- Show pagination only after filter is applied and records exist --}}
        @if((request()->filled('fromDate') || request()->filled('toDate') || request()->filled('service') || request()->filled('paymentMode')) 
            && $records && $records->count())
            <div class="d-flex justify-content-center mt-2">
                {{ $records->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/final_main.js') }}"></script>
@endsection
