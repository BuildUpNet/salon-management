@extends('Admin.layouts.main')

@section('title')
    Manage Staff
@endsection

@section('content')
<style>
    mark {
        background-color: #ffdd57; 
        color: black; 
        padding: 2px 4px;
        border-radius: 3px;
    }
</style>
    <div class="d-flex flex-column flex-grow-1">
        <!-- Main Content -->
        <div class="flex-grow-1 p-4 overflow-auto">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Staff Members</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStaffModal">Add
                        Staff</button>
                    <a href="{{ route('admin.manage') }}" class="btn btn-danger">Back</a>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.create-staff') }}" class="mb-3 d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search by name,mobile, role, or email..."
                    value="{{ request('search') }}" />
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var addStaffModal = new bootstrap.Modal(document.getElementById('addStaffModal'));
                        addStaffModal.show();
                    });
                </script>
            @endif
            @php
                function highlightText($text, $search)
                {
                    if (!$search) {
                        return $text;
                    }
                    return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark>$1</mark>', $text);
                }
            @endphp

            <!-- Table -->
            <table class="table table-bordered staff-table">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Staff ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="staffTableBody">
                    @forelse($staffs as $index => $staff)
                        <tr>
                            <td>{{ ($staffs->currentPage() - 1) * $staffs->perPage() + $index + 1 }}</td>
                           <td>{{ $staff->empDetail->employee_id ?? 'N/A' }}</td>
                            <td>{!! highlightText($staff->name, request('search')) !!}</td>
                            <td>{!! highlightText($staff->role_type, request('search')) !!}</td>
                            <td>{!! highlightText($staff->mobile, request('search')) !!}</td>
                            <td>{!! highlightText($staff->email, request('search')) !!}</td>
                            <td>
                                <button type="button" class="btn btn-warning editStaffBtn" data-id="{{ $staff->id }}"
                                    data-name="{{ $staff->name }}" data-role="{{ $staff->role_type }}"
                                    data-phone="{{ $staff->mobile }}" data-email="{{ $staff->email }}"
                                    data-bs-toggle="modal" data-bs-target="#editStaffModal">
                                    Edit
                                </button>

                                {{-- Delete --}}
                                <form action="{{ route('admin.delete-staff', $staff->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No staff members found.</td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="7">
                            <div class="d-flex justify-content-center mt-3">
                                {{ $staffs->links('pagination::bootstrap-5') }}
                            </div>
                        </td>
                    </tr>
                </tfoot>

            </table>
        </div>



        <!-- Modal -->
        <div class="modal fade" id="addStaffModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staffModalTitle">Add Staff</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>


                    <form action="{{ route('admin.store-staff') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                @include('Admin.staff.staff-form')
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editStaffModal" tabindex="-1" aria-labelledby="editStaffModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="" id="editStaffForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Staff</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_id" name="id">

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Role Type</label>
                                <select name="role_type" id="edit_role_type" class="form-select" required>
                                    <option value="Manager">Manager</option>
                                    <option value="Receptionist">Receptionist</option>
                                    <option value="Assistant">Assistant</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Mobile</label>
                                <input type="text" name="mobile" id="edit_mobile" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update Staff</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>




        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const buttons = document.querySelectorAll(".editStaffBtn");
                const form = document.getElementById("editStaffForm");

                buttons.forEach(button => {
                    button.addEventListener("click", () => {
                        const id = button.dataset.id;
                        const name = button.dataset.name;
                        const role = button.dataset.role;
                        const mobile = button.dataset.phone;
                        const email = button.dataset.email;

                        form.action = `/admin/update-staff/${id}`;
                        document.getElementById("edit_name").value = name;
                        document.getElementById("edit_role_type").value = role;
                        document.getElementById("edit_mobile").value = mobile;
                        document.getElementById("edit_email").value = email;
                    });
                });
            });
        </script>
    @endsection
