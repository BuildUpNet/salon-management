@extends('AdminReports.layouts.main')

@section('title', 'Admin Reports')

@section('content')
<div class="container-fluid mt-4">
    <div class="row g-4 justify-content-start">

        <!-- Date Wise Report -->
        <div class="col-md-6 col-lg-2">
            <a href="{{ route('adminreports.date') }}" class="text-decoration-none">
                <div class="card text-white bg-secondary text-center h-100 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-calendar-check fs-1"></i>
                        <h5 class="card-title mt-2">Date Wise Report</h5>
                    </div>
                </div>
            </a>
        </div>

        <!-- Chair Wise Report -->
        <div class="col-md-6 col-lg-2">
            <a href="{{ route('adminreports.chair') }}" class="text-decoration-none">
                <div class="card text-white bg-danger text-center h-100 shadow-sm">
                    <div class="card-body">
                        <img src="https://img.icons8.com/ios-filled/64/ffffff/barber-chair.png" alt="Chair Icon" class="mb-2" />
                        <h5 class="card-title">Chair Wise Report</h5>
                    </div>
                </div>
            </a>
        </div>

        <!-- Service Wise Report -->
        <div class="col-md-6 col-lg-2">
            <a href="{{ route('adminreports.service') }}" class="text-decoration-none">
                <div class="card text-white bg-success text-center h-100 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-person-gear fs-1"></i>
                        <h5 class="card-title mt-2">Service Wise Report</h5>
                    </div>
                </div>
            </a>
        </div>

        <!-- Product Wise Report -->
        <div class="col-md-6 col-lg-2">
            <a href="{{ route('adminreports.product') }}" class="text-decoration-none">
                <div class="card text-white bg-info text-center h-100 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-box-seam fs-1"></i>
                        <h5 class="card-title mt-2">Product Wise Report</h5>
                    </div>
                </div>
            </a>
        </div>

          <!-- Branch Wise Report -->
        <div class="col-md-6 col-lg-2">
            <a href="{{ route('adminreports.branch') }}" class="text-decoration-none">
                <div class="card text-white bg-warning text-center h-100 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-box-seam fs-1"></i>
                        <h5 class="card-title mt-2">Branch Wise Report</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
