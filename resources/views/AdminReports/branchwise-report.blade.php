@extends('AdminReports.layouts.main')

@section('title', 'Branch Wise Report')

@section('content')
    <div class="container mt-4 flex-grow-1">
        <h3><i class="bi bi-diagram-3"></i> Branch Wise Report</h3>

        <!-- Filter Form (optional if needed later) -->

        <form class="row g-3 mt-3 mb-4" method="POST" action="{{ route('adminreports.branch') }}">
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
                <label for="branch_id" class="form-label">Branch</label>
                <select id="branch_id" name="branch_id" class="form-select">
                    <option value="">All Branches</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->branch_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-warning w-100">Filter</button>
            </div>
        </form>


        <div class="table-responsive">
            <table class="table table-bordered table-striped" style="table-layout: fixed; width: 87%;">
                <thead class="table-dark">
                    <tr>
                        <th>Branch Name</th>
                        <th>Total Employees</th>
                        <th>Total Chairs</th>
                        <th>Completed Orders</th>
                    </tr>
                </thead>

                <tbody>
                    @if (request('fromDate') || request('toDate') || request('branch_id'))
                        @forelse($branchData as $data)
                            <tr>
                                <td>{{ $data->branch_id }}</td>
                                <td>{{ $data->emp_detail }}</td>
                                <td>{{ $data->chair_detail }}</td>
                                <td>{{ $data->completed_orders }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No branch data available.</td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="4" class="text-center text-muted">Please apply filter to view data.</td>
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
