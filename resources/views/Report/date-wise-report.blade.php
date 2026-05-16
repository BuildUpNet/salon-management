@extends('Branch.layouts.main')

@section('title', 'Date Wise Report')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
@endsection

@section('content')
<style>
    .pagination {
        justify-content: end;
        margin-top: 0.4rem;
    }
    .pagination .page-item .page-link {
        color: #000;
        border: 1px solid #ddd;
    }
    .pagination .page-item.active .page-link {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }
</style>

<div class="container mt-4 flex-grow-1">
    <h3 class="mb-3"><i class="bi bi-calendar-check"></i> Date Wise Bill Report</h3>

    <form method="GET" action="{{ url('Report/reports/date-wise') }}" class="row g-3 mb-2 align-items-end">
        <div class="col-md-2">
            <label for="fromDate" class="form-label">From</label>
            <input type="date" class="form-control" name="from_date" required value="{{ request('from_date') }}">
        </div>
        <div class="col-md-2">
            <label for="toDate" class="form-label">To</label>
            <input type="date" class="form-control" name="to_date" required value="{{ request('to_date') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-warning w-100">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center" style="table-layout: auto; width: 85%;">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Mobile</th>
                    <th>Chair</th>
                    <th>Bill No</th>
                    <th>Services</th>
                    <th>Products</th>
                    <th>Amount</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                @php $i = ($reports->currentPage() - 1) * $reports->perPage() + 1; @endphp
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $report->date }}</td>
                        <td>{{ $report->mobile }}</td>
                        <td>{{ $report->chair_id }}</td>
                        <td>{{ $report->bill_no }}</td>
                        <td>{{ $report->services ?? '-' }}</td>
                        <td>{{ $report->products ?? '-' }}</td>
                        <td>{{ number_format($report->amount, 2) }}</td>
                        <td>{{ $report->payment_type }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="text-end"><strong>Grand Total:</strong></td>
                    <td colspan="2"><strong>{{ number_format($totalAmount, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        @if ($reports && $reports->hasPages())
            <div class="mt-3 d-flex justify-content-center">
                {{ $reports->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/final_main.js') }}"></script>
@endsection
