@extends('Branch.layouts.main')

@section('title', 'Report')

@section('content')
  <div class="container mt-4 flex-grow-1">
            <div class="d-flex flex-wrap gap-3">

                <!-- Date Wise Report -->
                <a href="{{ route('reports.date') }}" class="text-decoration-none">
                    <div class="status-card bg-secondary text-white text-center p-3 rounded">
                        <i class="bi bi-calendar-check fs-1"></i>
                        <div class="mt-2">Date Wise Report</div>
                    </div>
                </a>

                <!-- Chair Wise Report -->
                <a href="{{ route('reports.chair') }}" class="text-decoration-none">
                    <div class="status-card bg-danger text-white text-center p-3 rounded">
                        <img src="https://img.icons8.com/ios-filled/64/ffffff/barber-chair.png" alt="Chair Icon"
                            width="48" height="48" style="margin-bottom: 20px;" />
                        <div>Chairs Wise Report</div>
                    </div>
                </a>

                <!-- Service Wise Report -->
                <a href="{{ route ('reports.service') }}" class="text-decoration-none">
                    <div class="status-card bg-success text-white text-center p-3 rounded">
                        <i class="bi bi-person-gear fs-1"></i>
                        <div class="mt-2">Service Wise Report</div>
                    </div>
                </a>

                <!-- Product Wise Report -->
                <a href="{{ route ('reports.product') }}" class="text-decoration-none">
                    <div class="status-card bg-info text-white text-center p-3 rounded">
                        <i class="bi bi-box-seam fs-1"></i>
                        <div class="mt-2">Product Wise Report</div>
                    </div>
                </a>
                 
                <a href="{{ route ('reports.staff') }}" class="text-decoration-none">
                    <div class="status-card bg-dark text-white text-center p-3 rounded">
                        <i class="bi bi-box-seam fs-1"></i>
                        <div class="mt-2">Staff Attedence Report</div>
                    </div>
                </a>

            </div>
        </div>
    </div>
@endsection


