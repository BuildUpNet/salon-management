@extends('Admin.layouts.main')

@section('title')
    Manage Branch
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
            <div id="branchSection">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Branch Management</h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#branchModal">Add
                            Branch</button>
                        <a href="{{ route('admin.manage') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.manage-branches') }}" class="mb-3 d-flex gap-2">
                    <input type="text" name="search" id="branchSearchInput" class="form-control"
                        placeholder="Search by name ,id or state..." value="{{ request('search') }}" />
                    <button type="submit" class="btn btn-primary" id="branchSearchBtn">Search</button>
                </form>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var branchModal = new bootstrap.Modal(document.getElementById('branchModal'));
                            branchModal.show();
                        });
                    </script>
                @endif
                <!-- Table -->
                @php
                    function highlight($text, $search)
                    {
                        if (!$search) {
                            return $text;
                        }
                        return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark>$1</mark>', $text);
                    }
                @endphp
                <table class="table table-bordered branch-table">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Branch Name</th>
                            <th>Login Email</th>
                            <th>Otp Email</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Postal Code</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Number of Chairs</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="branchTableBody">
                        @forelse($branches as $index => $branch)
                            <tr>
                                <td>{{ ($branches->currentPage() - 1) * $branches->perPage() + $index + 1 }}</td>

                               <td>{!! highlight($branch->branch_name, request('search')) !!}</td>
                                <td>{!! highlight($branch->email, request('search')) !!}</td>
                                <td>{{ $branch->otp_email }}
                                <td>{{ $branch->city }}</td>
                                <td>{!! highlight($branch->state, request('search')) !!}</td>
                                <td>{{ $branch->postal_code }}</td>
                                <td>{{ $branch->latitude }}</td>
                                <td>{{ $branch->longitude }}</td>
                                <td>{{ $branch->number_of_chairs }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning editBranchBtn"
                                        data-id="{{ $branch->id }}" data-branch_name="{{ $branch->branch_name }}"
                                        data-city="{{ $branch->city }}" data-state="{{ $branch->state }}"
                                        data-postal_code="{{ $branch->postal_code }}"
                                        data-latitude="{{ $branch->latitude }}" data-longitude="{{ $branch->longitude }}"
                                        data-chairs="{{ $branch->number_of_chairs }}" data-bs-toggle="modal"
                                        data-bs-target="#Updatemodel">
                                        Edit
                                    </button>


                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.delete-branch', $branch->id) }}" method="POST"
                                        style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No branches found.</td>
                            </tr>
                        @endforelse

                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="11">
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $branches->links('pagination::bootstrap-5') }}
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- ADD BRANCH MODAL -->
        <div class="modal fade" id="branchModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Branch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('admin.add-branch') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                @include('Admin.form.branch-form-fields')
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- EDIT BRANCH MODAL -->
        <div class="modal fade" id="Updatemodel" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Branch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form id="branchForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="PUT">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Branch Name</label>
                                    <input type="text" name="branch_name" class="form-control" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" name="state" class="form-control" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" name="postal_code" class="form-control" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="number" name="latitude" step="any" class="form-control" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="number" name="longitude" step="any" class="form-control" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Number of Chairs</label>
                                    <input type="number" name="number_of_chairs" class="form-control" required />
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('branchForm');
                const methodInput = document.getElementById('formMethod');

                const nameInput = form.querySelector('[name="branch_name"]');
                const cityInput = form.querySelector('[name="city"]');
                const stateInput = form.querySelector('[name="state"]');
                const postalInput = form.querySelector('[name="postal_code"]');
                const latInput = form.querySelector('[name="latitude"]');
                const lngInput = form.querySelector('[name="longitude"]');
                const chairsInput = form.querySelector('[name="number_of_chairs"]');

                document.querySelectorAll('.editBranchBtn').forEach(button => {
                    button.addEventListener('click', () => {
                        const id = button.dataset.id;
                        nameInput.value = button.dataset.branch_name;
                        cityInput.value = button.dataset.city;
                        stateInput.value = button.dataset.state;
                        postalInput.value = button.dataset.postal_code;
                        latInput.value = button.dataset.latitude;
                        lngInput.value = button.dataset.longitude;
                        chairsInput.value = button.dataset.chairs;

                        form.action = `/admin/update-branch/${id}`;
                        methodInput.value = 'PUT';
                    });
                });
            });
        </script>
    @endsection
