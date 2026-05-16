@extends('Branch.layouts.main')

@section('content')
    <div class="container" style="max-width: 1200px; margin-top: 2rem;">
        <div class="card shadow-sm rounded">
            <div class="card-header bg-dark text-white d-flex align-items-center">
                <i class="fas fa-cog me-2"></i>
                <h5 class="mb-0">Branch Profile Settings</h5>
            </div>

            <div class="card-body">
                {{-- Basic Info --}}
                <form action="{{ route('updateProfile') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                    @csrf
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Basic Info</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Branch Name</label>
                            <input type="text" name="branch_name" class="form-control"
                                value="{{ $branch->branch_name ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $branch->email }}" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email ID for Receive OTP</label>

                            <input type="email" name="otp_email"
                                class="form-control {{ !empty($user->pending_otp_email) ? 'border-danger text-danger' : 'border-success text-success' }}"
                                value="{{ old('otp_email', $user->pending_otp_email ?? $user->otp_email) }}"
                                {{ !empty($user->pending_otp_email) ? 'readonly' : '' }} />

                            @if (!empty($user->pending_otp_email))
                                <small class="text-danger">Pending approval</small>
                            @else
                                <small class="text-success">Approved Email</small>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4 mb-2 align-items-center">
                        <div class="col-md-5">
                            <label class="form-label d-block">Branch Logo</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="border rounded-circle overflow-hidden" style="width: 70px; height: 70px;">
                                    @if ($branch->logo)
                                        <img src="{{ asset('uploads/logos/' . $branch->logo) }}" alt="Logo"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('default-logo.png') }}" alt="No Logo"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="logo" class="form-control form-control-sm">
                                    <small class="text-muted">Allowed: JPG, PNG | Max: 2MB</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mt-3 mt-md-0">
                            <button type="submit" class="btn btn-success w-100">Update Profile</button>
                        </div>
                    </div>

                </form>

                {{-- Address Info --}}
                <form action="{{ route('updateAddress') }}" method="POST" class="mb-4">
                    @csrf
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Address Info</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="{{ $branch->city }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control" value="{{ $branch->state }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="form-control"
                                value="{{ $branch->postal_code }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" class="form-control" value="{{ $branch->latitude }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" class="form-control" value="{{ $branch->longitude }}">
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-warning w-100 text-dark">Update Address</button>
                        </div>
                    </div>
                </form>


                <form action="{{ route('changePassword') }}" method="POST">
                    @csrf
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Change Password</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-md-3 mt-5">
                            <button type="submit" class="btn btn-danger w-100">Change Password</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    </div>
    <!-- OTP Verification Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('changePasswordOtp') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpModalLabel">Verify OTP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>An OTP has been sent to your registered email. Please enter it below:</p>
                    <div class="mb-3">
                        <label class="form-label">Enter OTP</label>
                        <input type="text" name="otp" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Verify & Update Password</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- SweetAlert for success -->
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        });
    </script>
@endif

<!-- SweetAlert for general errors -->
@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    </script>
@endif

<!-- SweetAlert for validation errors -->
@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    </script>
@endif

    <script>
     document.addEventListener('DOMContentLoaded', function () {
        @if(session('otp_sent'))
            var otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
            otpModal.show();
        @endif
    });
    </script>
@endsection
