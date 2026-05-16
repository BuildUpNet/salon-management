@extends('Admin.layouts.main')

@section('title')
    Manage Services
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
                <h4>Service List</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addServiceModal">Add
                        Service</button>
                    <a href="{{ route('admin.manage') }}" class="btn btn-danger">Back</a>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.manage-service') }}" class="mb-3 d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search by service_name or service_id..."
                    value="{{ request('search') }}" />
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const modal = new bootstrap.Modal(document.getElementById('addServiceModal'));
                        modal.show();
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

            <!-- Service Table -->
            <table class="table table-bordered mt-3 service-table">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Service ID</th>
                        <th>Service Name</th>
                        <th>Gender</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="serviceTableBody">

                    @foreach ($services as $index => $service)
                        <tr>
                            <td>{{ ($services->currentPage() - 1) * $services->perPage() + $index + 1 }}</td>
                            <td>{!! highlightText( $service->service_id, request('search')) !!}</td>
                            <td>{!! highlightText($service->service_name, request('search')) !!}</td>
                            <td>{{ $service->gender }}</td>
                            <td>{{ $service->duration }}</td>
                            <td>{{ $service->price }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning editServiceBtn"
                                    data-id="{{ $service->id }}" data-service_name="{{ $service->service_name }}"
                                    data-gender="{{ $service->gender }}" data-duration="{{ $service->duration }}"
                                    data-price="{{ $service->price }}" data-bs-toggle="modal"
                                    data-bs-target="#editServiceModal">
                                    Edit
                                </button>

                                <form action="{{ route('admin.delete-service', $service->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7">
                            <div class="d-flex justify-content-center mt-3">
                                {{ $services->links('pagination::bootstrap-5') }}
                            </div>
                        </td>
                    </tr>
                </tfoot>

            </table>
        </div>

        <!-- Service Modal -->
        <div class="modal fade" id="addServiceModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('admin.add-service') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                @include('Admin.manage-service.service')
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="editServiceModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editServiceForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Service</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="_method" id="formMethod" value="PUT">

                                <div class="mb-3">
                                    <label class="form-label">Service Name</label>
                                    <input type="text" name="service_name" id="edit_service_name" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" id="edit_gender" class="form-select">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Unisex">Unisex</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Duration</label>
                                    <input type="number" name="duration" id="edit_duration" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="text" name="price" id="edit_price" class="form-control">
                                </div>

                                {{-- <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" id="edit_category" class="form-select">
                                        <option value="Haircut">Haircut</option>
                                        <option value="Facial">Facial</option>
                                        <option value="Spa">Spa</option>
                                        <option value="Massage">Massage</option>
                                    </select>
                                </div>
                            </div> --}}

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button class="btn btn-primary" type="submit">Update</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('editServiceForm');
                const methodInput = document.getElementById('formMethod');

                const nameInput = form.querySelector('[name="service_name"]');
                const genderInput = form.querySelector('[name="gender"]');
                const durationInput = form.querySelector('[name="duration"]');
                const priceInput = form.querySelector('[name="price"]');


                document.querySelectorAll('.editServiceBtn').forEach(button => {
                    button.addEventListener('click', () => {
                        const id = button.dataset.id;
                        nameInput.value = button.dataset.service_name;
                        genderInput.value = button.dataset.gender;
                        durationInput.value = button.dataset.duration;
                        priceInput.value = button.dataset.price;


                        form.action = `/admin/update-service/${id}`;
                        methodInput.value = 'PUT';
                    });
                });
            });
        </script>
    @endsection
