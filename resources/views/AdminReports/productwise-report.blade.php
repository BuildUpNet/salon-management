@extends('AdminReports.layouts.main')

@section('title', 'Product Wise Report')

@section('content')
    <div class="container mt-4 flex-grow-1">
        <h3><i class="bi bi-box-seam"></i> Product Wise Report</h3>

        <!-- Filter Form -->
        <form class="row g-3 mt-3 mb-4" method="POST" action="{{ route('adminreports.product') }}">
            @csrf
            <div class="col-md-2">
                <label for="fromDate" class="form-label">From Date</label>
                <input type="date" id="fromDate" name="fromDate" class="form-control" value="{{ request('fromDate') }}" />
            </div>
            <div class="col-md-2">
                <label for="toDate" class="form-label">To Date</label>
                <input type="date" id="toDate" name="toDate" class="form-control" value="{{ request('toDate') }}" />
            </div>
            <div class="col-md-2">
                <label for="product" class="form-label">Product</label>
                <select id="product" name="product" class="form-select">
                    <option value="">All Products</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->product_name }}"
                            {{ request('product') == $product->product_name ? 'selected' : '' }}>
                            {{ $product->product_name }}
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

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle"
                style="table-layout: fixed; width: 85%;">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%;">S.No.</th>
                        <th style="width: 15%;">Date</th>
                        <th style="width: 15%;">Branch</th>
                        <th style="width: 15%;">Customer</th>
                        <th style="width: 15%;">Product</th>
                        <th style="width: 15%;">Payment Mode</th>
                        <th style="width: 15%;">Amount ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @if (request()->filled('fromDate') ||
                            request()->filled('toDate') ||
                            request()->filled('product') ||
                            request()->filled('paymentMode'))
                        @forelse ($records as $index => $record)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                                <td>{{ $record->branch_id }}</td>
                                <td>{{ $record->mobile }}</td>
                                <td>{{ $record->product_name }}</td>
                                <td>{{ ucfirst($record->payment_type) }}</td>
                                <td>${{ number_format($record->product_price, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No records found.</td>
                            </tr>
                        @endforelse

                        @if ($records->count())
                            <tr class="fw-bold table-dark">
                                <td colspan="6" class="text-end">Total Amount</td>
                                <td>${{ number_format($records->sum('product_price'), 2) }}</td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Please apply a filter to view product-wise report data.
                            </td>
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
