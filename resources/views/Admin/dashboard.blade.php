@extends('Admin.layouts.main')

@section('title')
    Dashboard
@endsection


@section('content')
    <style>
        @keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .blinking {
            animation: blink 1s infinite;
        }
    </style>
    <div class="d-flex flex-column flex-grow-1">

        <!-- Main Content -->
        <div class="container mt-4 flex-grow-1">
            <div class="d-flex flex-wrap gap-3">

                <div class="status-card bg-secondary text-white">
                    <i class="bi bi-shop-window fs-1"></i>
                    <div>Total Outlet</div>
                    <div class="fs-4" id="branchCount">{{ $branchCount }}</div>
                </div>

                <div class="status-card bg-danger text-white">
                    <img src="https://img.icons8.com/ios-filled/64/ffffff/barber-chair.png" alt="Chair Icon" width="48"
                        height="48" style="margin-bottom: 8px;" />
                    <div>Total Chairs</div>
                    <div class="fs-4" id="totalChairs">{{ $totalChairs }}</div>
                </div>

                <div class="status-card bg-success text-white">
                    <i class="bi bi-check-circle-fill fs-1"></i>
                    <div>Available Chairs</div>
                    <div class="fs-4" id="availableCount">{{ $availableChairs }}</div>
                </div>

                <div class="status-card bg-info text-white">
                    <i class="bi bi-people-fill fs-1"></i>
                    <div>Staff Members</div>
                    <div class="fs-4" id="staffCount">{{ $staffCount }}</div>
                </div>

                <div class="status-card bg-warning text-dark">
                    <i class="bi bi-hourglass-split fs-1"></i>
                    <div>Pending Orders</div>
                    <div class="fs-4" id="pendingOrders">{{ $pendingOrders }}</div>
                </div>

                <div class="status-card bg-info text-white">
                    <i class="bi bi-person-exclamation fs-1"></i>
                    <div>Waiting Clients</div>
                    <div class="fs-4" id="waitingClients">{{ $waitingClients }}</div>
                </div>

                <div class="status-card bg-primary text-white">
                    <i class="bi bi-bag-check-fill fs-1"></i>
                    <div>Total Orders</div>
                    <div class="fs-4" id="totalOrders">{{ $totalOrders }}</div>
                </div>

                <div class="status-card bg-dark text-white">
                    <i class="bi bi-patch-check-fill fs-1"></i>
                    <div>Complete Orders</div>
                    <div class="fs-4" id="completedOrders">{{ $completedOrders }}</div>
                </div>
                <div class="status-card bg-success text-white" id="otpCard" style="cursor: pointer;"
                    onclick="showEmailPopup()">
                    <i class="bi bi-envelope-fill fs-1"></i>
                    <div>Approved Emails</div>
                    <div class="fs-4" id="approvedOtpCount">{{ $approvedOtpCount }}</div>
                </div>

            </div>
        </div>
        <!-- Email Modal -->
        <div class="modal fade" id="emailModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pending OTP Email Approvals</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Branch ID</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="emailTableBody">
                                <!-- Dynamic content -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const count = parseInt(document.getElementById("approvedOtpCount").innerText);
                if (count > 0) {
                    document.getElementById("otpCard").classList.add("blinking");
                }
            });
        </script>
        <script>
            function showEmailPopup() {
                fetch('/admin/get-approved-emails')
                    .then(res => res.json())
                    .then(data => {
                        const tbody = document.getElementById('emailTableBody');
                        tbody.innerHTML = '';

                        if (data.length === 0) {
                            tbody.innerHTML = `<tr><td colspan="3" class="text-center">No pending emails.</td></tr>`;
                        } else {
                            data.forEach(row => {
                                tbody.innerHTML += `
                                <tr>
                                    <td>${row.email}</td>
                                    <td>${row.branch_id}</td>
                                    <td>
                                     <button class="btn btn-success btn-sm" onclick="approveEmail('${row.branch_id}')">Approve</button>

                                        <button class="btn btn-danger btn-sm" onclick="denyEmail(${row.branch_id})">Deny</button>
                                    </td>
                                </tr>
                            `;
                            });
                        }

                        new bootstrap.Modal(document.getElementById('emailModal')).show();
                    });
            }

           function approveEmail(branchId) {
    fetch('/admin/approve-otp-email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            branch_id: branchId
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Optionally reload UI or update the row
        } else {
            alert(data.message);
        }
    });
}

            function denyEmail(id) {
                fetch(`/admin/deny-email/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    }
                }).then(res => res.json()).then(data => {
                    alert(data.message);
                    showEmailPopup();
                });
            }
        </script>
    @endsection
