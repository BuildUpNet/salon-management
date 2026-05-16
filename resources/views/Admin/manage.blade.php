 @extends('Admin.layouts.main')

@section('title')
Dashboard
@endsection

@section('content')
 <div class="d-flex flex-column flex-grow-1">

        <!-- Main Content Area -->
        <div class="container mt-4 flex-grow-1">
            <div class="d-flex flex-wrap gap-3">

                <div class="status-cardM bg-info text-white text-center">
                    <a href="{{ route('admin.manage-branches') }}" class="text-white text-decoration-none">
                        <i class="fas fa-store fa-2x"></i>
                        <div>Manage Branch</div>
                    </a>
                </div>

                <div class="status-cardM bg-warning text-white text-center">
                    <a href="{{ route('admin.create-staff') }}" class="text-white text-decoration-none">
                        <i class="fas fa-user-friends fa-2x"></i>
                        <div>Manage Staff</div>
                    </a>
                </div>

                <div class="status-cardM bg-success text-white text-center">
                    <a href="{{ route('admin.manage-service') }}" class="text-white text-decoration-none">
                        <i class="fas fa-scissors fa-2x"></i>
                        <div>Manage Services</div>
                    </a>
                </div>

                <div class="status-cardM bg-dark text-white text-center">
                    <a href="{{ route('admin.manage-product') }}" class="text-white text-decoration-none">
                        <i class="fas fa-box fa-2x"></i>
                        <div>Manage Product</div>
                    </a>
                </div>

            </div>
        </div>
@endsection