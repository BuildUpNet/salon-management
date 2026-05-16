@extends('Branch.layouts.main')

@section('title', 'Staff Attendance')

@section('content')
    <div class="container-lg mt-4">
        <h4>Attendance for {{ $date }}</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Sr. No.</th>
                    <th>Employee ID</th>
                    <th>Staff Name</th>
                    <th>Role</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Hours</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                  @php $sr = 1; @endphp
                @foreach ($employees as $emp)
                    @php
                        $logs = \App\Models\Attendance::where('emp_id', $emp->employee_id)
                            ->where('branch_id', auth()->user()->branch_id)
                            ->where('date', $date)
                            ->orderBy('check_in')
                            ->get();
                        $lastLog = $logs->last();
                    @endphp

                    @if ($logs->isEmpty())
                        <tr>
                           <td>{{ $sr++ }}</td>
                            <td>{{ $emp->employee_id }}</td>
                            <td>{{ $emp->name }}</td>
                            <td>{{ $emp->role_type }}</td>
                            <td colspan="3" class="text-center text-muted">No records found</td>
                            <td>
                                <form action="{{ route('branch.attendance.checkin', $emp->employee_id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Check In</button>
                                </form>
                            </td>
                        </tr>
                    @else
                        @foreach ($logs as $log)
                            @php
                                $checkIn = $log->check_in ? \Carbon\Carbon::parse($log->check_in) : null;
                                $checkOut = $log->check_out ? \Carbon\Carbon::parse($log->check_out) : null;
                                $hours = $checkIn && $checkOut ? $checkIn->diff($checkOut) : null;
                                $isLast = $log->id === $lastLog->id;
                            @endphp
                            <tr>
                                 <td>{{ $sr++ }}</td>
                                <td>{{ $emp->employee_id }}</td>
                                <td>{{ $emp->name }}</td>
                                <td>{{ $emp->role_type }}</td>
                                <td>{{ $log->check_in ?? '-' }}</td>
                                <td>{{ $log->check_out ?? '-' }}</td>
                                <td>
                                    @if($hours)
                                        <span class="text-success">{{ $hours->h }} hr {{ $hours->i }} min</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($isLast)
                                        @if (is_null($log->check_out) && !is_null($log->check_in))
                                            {{-- Allow check-out --}}
                                            <form action="{{ route('branch.attendance.checkout', $emp->employee_id) }}" method="POST"
                                                  onsubmit="return confirm('Check-Out {{ $emp->name }}?')">
                                                @csrf
                                                <button class="btn btn-danger btn-sm">Check Out</button>
                                            </form>
                                        @elseif (!is_null($log->check_out))
                                            {{-- Last log completed – allow new check-in --}}
                                            <form action="{{ route('branch.attendance.checkin', $emp->employee_id) }}" method="POST"
                                                  onsubmit="return confirm('Check-In {{ $emp->name }} again?')">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Check In</button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
