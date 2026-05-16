@extends('Branch.layouts.main')

@section('title')
    Appointment
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/appointment.css') }}" />
@endsection

@section('content')
    <!-- Main Content -->
    <div class="col custom-main-content bg-light">

        <h5 class="custom-section-title">Order Status</h5>
        <div class="d-flex gap-2 flex-wrap mb-3">

            <div class="custom-status-cards bg-success text-white">
                <div class="status-content">
                    <i class="bi bi-check-circle-fill"></i>
                    <div class="text-label">
                        <div>Available</div>
                        <strong id="availableCount">{{ $availableCount }}</strong>
                    </div>
                </div>
            </div>

            <div class="custom-status-cards bg-warning text-dark">
                <div class="status-content">
                    <i class="bi bi-hourglass-split"></i>
                    <div class="text-label">
                        <div>Waiting</div>
                        <strong id="waitingCount">{{ $waitingCount }}</strong>

                    </div>
                </div>
            </div>

            <div class="custom-status-cards bg-danger text-white">
                <div class="status-content">
                    <i class="bi bi-person-fill-lock"></i>
                    <div class="text-label">
                        <div>Pending</div>
                        <strong id="occupiedCount">{{ $pendingCount }}</strong>
                    </div>
                </div>
            </div>

            <div class="custom-status-cards bg-info text-white">
                <div class="status-content">
                    <i class="bi bi-calendar-check-fill"></i>
                    <div class="text-label">
                        <div>Booked</div>
                        <strong id="bookedCount">{{ $bookedCount }}</strong>

                    </div>
                </div>
            </div>

            <div class="custom-status-cards bg-dark text-white">
                <div class="status-content">
                    <i class="bi bi-list-check"></i>
                    <div class="text-label">
                        <div>Complete Order</div>
                        <strong id="totalCount">{{ $totalBillCount }}</strong>

                    </div>
                </div>
            </div>

        </div>


        <h5 class="custom-section-title">Chairs Status</h5>

        <!-- Chair Boxes (Grid Layout) -->
        <div class="d-flex flex-wrap gap-3">
            @forelse($chairs as $chair)
                <div class="text-center">
                    <!--<div><strong>Chair {{ $loop->iteration }}</strong></div>-->
                    <div class="chair-box text-white text-center p-2 chair-clickable" data-chair-id="{{ $chair->chair_id }}"
                        id="chairBox_{{ $chair->chair_id }}"
                        style="background-color: {{ $chair->status ? 'red' : 'green' }};
            border-radius: 7px;   width: 100px;
                    height: 100px; cursor: pointer;">
                        <i class="fas fa-chair me-1"></i>
                        {{-- <img class="white-chair-icon" src="/barbar/assets/images/barber-chair.png" alt="Chair Icon"><br> --}}
                        <div><strong>Chair {{ $loop->iteration }}</strong></div>
                        <!--{{ $chair->chair_id }}-->
                        <div class="chair-timer small mt-1" id="timer_{{ $chair->chair_id }}"></div>
                    </div>
                </div>
            @empty
                <p>No chairs available.</p>
            @endforelse
        </div>


        <div class="mb-3 mt-5">
            <button id="btnServices" class="btn btn-info btn-sm">Services</button>
            <button id="btnProducts" class="btn btn-dark btn-sm">Products</button>
            <button id="btnPendingBill" class="btn btn-danger btn-sm">Pending Bill</button>
            <button id="btnWaiting" class="btn btn-warning btn-sm" onclick="loadWaitingList()">Waiting List</button>
        </div>

        <!-- Sections -->
        <div id="servicesContainer" class="custom-service-section mb-3 custom-items-row"></div>
        <div id="productsContainer" class="custom-service-section mb-3 custom-items-row" style="display: none;"></div>
        <div id="pendingBillContainer" class="mt-3" style="display: none;"></div>
        <!-- Waiting List Section -->

        <div id="waitingListContainer" class="table-responsive mt-3" style="display:none;">
            <table class="table table-bordered table-sm table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Sr no</th>
                        <th>Mobile</th>
                        <th>Name</th>
                        <th>Chair</th>
                        <th>Staff</th>
                        <th>Book Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="waitingListBody"></tbody>
            </table>

            <!-- pagination links will be inserted here -->
            <nav>
                <ul id="waitingPagination" class="pagination justify-content-end mb-0"></ul>
            </nav>
        </div>

        <script>
            const chairs = @json($chairs);
            const staffs = @json($staffs);
        </script>


    </div>

    <!-- Rightbar -->
    <div class="col-4 custom-rightbar">
        <h5 class="custom-section-title">Add New Appointment</h5>
        <form id="appointmentForm">
            @csrf
            <!-- Row 1: Phone & Name -->
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <input type="hidden" id="currentAppointmentId" value="">

            <div class="row mb-2">
                <div class="col">
                    <input type="text" name="mobile" id="custPhone" maxlength="10" class="form-control form-control-sm"
                        placeholder="Phone Number" autocomplete="off"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                </div>
                <div class="col">
                    <input type="text" name="name" id="custName" class="form-control form-control-sm"
                        placeholder="Name" />
                </div>
            </div>

            <!-- Row 2: Email & Chair -->
            <div class="row mb-2">
                <div class="col">
                    <input type="email" name="email" id="custEmail" class="form-control form-control-sm"
                        placeholder="Email" />
                </div>
                <div class="col">
                    <input type="text" name="chair" autocomplete="off" id="assignedChair"
                        class="form-control form-control-sm" placeholder="Chair" />
                </div>
            </div>

            <!-- Row 3: Senior Citizen & Gender -->
            <div class="row mb-2">
                <div class="col">
                    <select name="senior_citizen" id="seniorCitizen" class="form-select form-select-sm">
                        <option value="">Senior Citizen</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="col">
                    <select name="gender" id="gender" class="form-select form-select-sm">
                        <option value="">Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <select name="staff" id="staffSelect" class="form-select form-select-sm">
                        <option value="">Select Staff</option>
                        @foreach ($staffs as $staff)
                            @php
                                $status = optional($staff->empDetail)->status;
                                $color = $status == 0 ? 'green' : 'red';
                            @endphp
                            <option value="{{ $staff->name }}" style="color: {{ $color }}">
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>


        <!-- Bill Table -->
        <div class="table-wrapper bg-white text-dark mt-3 border rounded">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 10%;">Sr.no</th>
                        <th style="width: 30%;">Description</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 15%;">Duration</th>
                        <th style="width: 15%;">Amt</th>
                        <th style="width: 20%;">Action</th>
                    </tr>
                </thead>
            </table>

            <!-- Scrollable Body -->
            <div id="custom-serviceListScroll" style="max-height: 200px; overflow-y: auto;">
                <table class="table table-bordered table-sm mb-0">
                    <tbody id="serviceListBody">
                        <!-- Dynamic rows will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Billing Summary -->
        <div class="table-footer bg-white text-dark p-3 border rounded-bottom mt-1">
            <div class="row mb-1">
                <div class="col-6 text-end"><strong>Subtotal:</strong></div>
                <div class="col-6" id="subtotalAmount">$0.00</div>
            </div>
            <div class="row mb-2">
                <div class="col-6 text-end"><strong>Discount:</strong></div>
                <div class="col-6">
                    <div class="input-group input-group-sm">
                        <input type="number" id="discountValue" class="form-control" min="0"
                            placeholder="Discount" />
                        <select id="discountType" class="form-select">
                            <option value="flat">$</option>
                            <option value="percent">%</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-6 text-end"><strong>MSF (1.1%):</strong></div>
                <div class="col-6" name="msf" id="msfAmount">$0.00</div>
            </div>
            <div class="row mb-1">
                <div class="col-6 text-end"><strong>Total:</strong></div>
                <div class="col-6" name="totalamount" id="totalAmount">$0.00</div>
            </div>
            <div class="row mb-1">
                <div class="col-6 text-end"><strong>Cash Received:</strong></div>
                <div class="col-6">
                    <input type="number" id="cashReceivedInput" class="form-control form-control-sm" />
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-6 text-end"><strong>Change:</strong></div>
                <div class="col-6" id="changeAmount">$0.00</div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <button class="btn btn-success btn-sm" id="bookBtn">Book</button>
            <button class="btn btn-warning btn-sm" id="payBtn">Pay</button>
            <button class="btn btn-info btn-sm" type="button" id="checkoutBtn">Checkout</button>
            <button type="button" class="btn btn-danger" id="cancelBookingBtn">Cancel Booking</button>
        </div>
    </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
     document.getElementById('cancelBookingBtn').addEventListener('click', function () {
    const appointmentId = document.getElementById('currentAppointmentId').value;

    if (!appointmentId) {
        Swal.fire({
            icon: 'warning',
            title: 'No Appointment Selected',
            text: 'Please double-click an occupied chair first.'
        });
        return;
    }

    Swal.fire({
        icon: 'warning',
        title: 'Are you sure?',
        text: 'This will cancel the booking and free the chair and staff.',
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it!'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/Branch/appointment/cancel/${appointmentId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cancelled',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Could not cancel booking.'
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while cancelling.'
                });
            });
        }
    });
});

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('cancel-btn')) {
                const id = e.target.getAttribute('data-id');
                if (confirm('Are you sure you want to cancel the waiting appointment?')) {
                    fetch(`/branch/cancel-waiting/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Cancelled successfully.');
                                loadWaitingList(); // reload list
                            } else {
                                alert('Cancellation failed.');
                            }
                        });
                }
            }
        });
        $(document).on('click', '.pending-pay-btn', function() {
            const mobile = $(this).data('mobile');
            const appointmentId = $(this).data('appointment-id');

            fetch(`/Branch/pendingbill/${mobile}/${appointmentId}`)
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        // ✅ Fill form fields
                        $('#custPhone').val(response.customer.mobile || '');
                        $('#custName').val(response.customer.name || '');
                        $('#custEmail').val(response.customer.email || '');
                        $('#gender').val(response.customer.gender || '');
                        $('#seniorCitizen').val(response.customer.senior_citizen || '');
                        $('#assignedChair').val(response.chair_id || '');

                        if ($('#staffSelect').length) {
                            $('#staffSelect').val(response.staff_name).trigger('change');

                        }

                        // ✅ Clear tables
                        $('#serviceListBody').html('');
                        $('#productListBody').html('');

                        // ✅ Add services
                        response.services.forEach(service => {
                            const srNo = getNextSerialNumber();
                            const row = `
                        <tr data-service-id="${service.id}">
                            <td style="width: 10%;">${srNo}</td>
                            <td style="width: 30%;">${service.service_name}</td>
                            <td style="width: 10%;" class="service-qnty"><span class="qty-value">${service.service_qnty || 1}</span></td>
                            <td style="width: 15%;">${service.service_duration}</td>
                            <td style="width: 15%;" class="service-amount">$${service.service_price}</td>
                            <td style="width: 20%;">
                                <button class="btn btn-sm btn-success me-1" onclick="increaseServiceQty(this, ${service.service_price})">+</button>
                                <button class="btn btn-sm btn-warning me-1" onclick="decreaseServiceQty(this, ${service.service_price})">-</button>
                            </td>
                        </tr>
                    `;
                            $('#serviceListBody').append(row);
                        });

                        // ✅ Add products (fixed: appending to correct table)
                        response.products.forEach(product => {
                            const srNo = getNextSerialNumber();
                            const row = `
                        <tr data-product-id="${product.id}">
                            <td style="width: 10%;">${srNo}</td>
                            <td style="width: 30%;">${product.product_name}</td>
                            <td style="width: 10%;" class="product-qnty"><span class="qty-value">${product.product_qnty || 1}</span></td>
                            <td style="width: 15%;">-</td>
                            <td style="width: 15%;" class="product-amount">$${product.product_price}</td>
                            <td style="width: 20%;">
                                <button class="btn btn-sm btn-success" onclick="increaseProductQty(this, ${product.product_price})">+</button>
                                <button class="btn btn-sm btn-warning" onclick="decreaseProductQty(this, ${product.product_price})">-</button>
                            </td>
                        </tr>
                    `;
                            $('#serviceListBody').append(row); // ✅ FIXED HERE
                        });

                        // ✅ Update totals
                        updateBillingSummaryGlobal();

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Not Found',
                            text: response.message || 'Pending bill record not found.'
                        });
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again.'
                    });
                });
        });

        $(document).on('click', '.checkin-btn', function() {
            const mobile = $(this).data('mobile');

            fetch(`/Branch/mobile-appointment/${mobile}`)
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        // Fill form fields
                        document.getElementById('custPhone').value = response.customer.mobile || '';
                        document.getElementById('custName').value = response.customer.name || '';
                        document.getElementById('custEmail').value = response.customer.email || '';
                        document.getElementById('gender').value = response.customer.gender || '';
                        document.getElementById('seniorCitizen').value = response.customer.senior_citizen || '';
                        document.getElementById('assignedChair').value = response.chair_id || '';

                        if (document.getElementById('staffSelect')) {
                            document.getElementById('staffSelect').value = response.staff_name || '';
                        }

                        // Clear table
                        document.getElementById('serviceListBody').innerHTML = '';

                        // Add services
                        response.services.forEach(service => {
                            const srNo = getNextSerialNumber();
                            const row = document.createElement('tr');
                            row.setAttribute('data-service-id', service.id);
                            row.innerHTML = `
                        <td style="width: 10%;">${srNo}</td>
                        <td style="width: 30%;">${service.service_name}</td>
                        <td style="width: 10%;" class="service-qnty">
                            <span class="qty-value">${service.service_qnty || 1}</span>
                        </td>
                        <td style="width: 15%;">${service.service_duration} </td>
                        <td style="width: 15%;" class="service-amount">$${service.service_price}</td>
                        <td style="width: 20%;">
                            <button class="btn btn-sm btn-success me-1" onclick="increaseServiceQty(this, ${service.service_price})">+</button>
                            <button class="btn btn-sm btn-warning me-1" onclick="decreaseServiceQty(this, ${service.service_price})">-</button>
                        </td>
                    `;
                            document.getElementById('serviceListBody').appendChild(row);
                        });

                        // Add products
                        response.products.forEach(product => {
                            const srNo = getNextSerialNumber();
                            const row = document.createElement('tr');
                            row.setAttribute('data-product-id', product.id);
                            row.innerHTML = `
                        <td style="width: 10%;">${srNo}</td>
                        <td style="width: 30%;">${product.product_name}</td>
                        <td style="width: 10%;" class="product-qnty">
                            <span class="qty-value">${product.product_qnty || 1}</span>
                        </td>
                        <td style="width: 15%;">-</td>
                        <td style="width: 15%;" class="product-amount">$${product.product_price}</td>
                        <td style="width: 20%;">
                            <button class="btn btn-sm btn-success" onclick="increaseProductQty(this, ${product.product_price})">+</button>
                            <button class="btn btn-sm btn-warning" onclick="decreaseProductQty(this, ${product.product_price})">-</button>
                        </td>
                    `;
                            document.getElementById('serviceListBody').appendChild(row);
                        });
                        updateBillingSummaryGlobal();

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Not Found',
                            text: response.message || 'Customer record not found.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please check the console.'
                    });
                });
        });

        $('#checkoutBtn').on('click', function(e) {
            e.preventDefault(); // <== VERY IMPORTANT

            const chairId = $('#assignedChair').val();
            const mobile = $('#custPhone').val();
            const total = $('#subtotalAmount').val();

            if (!chairId || !mobile) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Info',
                    text: 'Chair ID or Mobile number is missing.'
                });
                return;
            }

            let formData = {
                services: [],
                products: []
            };

            document.querySelectorAll('#serviceListBody tr').forEach(row => {
                const serviceId = row.getAttribute('data-service-id');
                if (serviceId) {
                    formData.services.push({
                        id: serviceId,
                        name: row.children[1].textContent.trim(),
                        qnty: parseInt(row.querySelector('.service-qnty').textContent.trim(), 10),
                        duration: row.children[3].textContent.trim(),
                        price: parseFloat(row.querySelector('.service-amount').textContent.replace(
                            /[$₹]/g, '').trim())
                    });
                }

                const productId = row.getAttribute('data-product-id');
                if (productId) {
                    formData.products.push({
                        id: productId,
                        name: row.children[1].textContent.trim(),
                        product_qnty: parseInt(row.querySelector('.product-qnty').textContent
                            .trim(), 10),
                        price: parseFloat(row.querySelector('.product-amount').textContent.replace(
                            /[$₹]/g, '').trim())
                    });
                }
            });


            $.ajax({
                url: '/checkout',
                method: 'POST',
                contentType: 'application/json',
                processData: false,
                data: JSON.stringify({
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    chairId: chairId,
                    mobile: mobile,
                    total: total,
                    services: formData.services,
                    products: formData.products
                }),
                success: function(res) {
                    if (res.success) {
                        updateBillingSummaryGlobal();
                        Swal.fire({
                            icon: 'success',
                            title: 'Checkout Complete',
                            text: 'Customer moved to pending bills.',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                            loadPendingBills(); // optional, will be cleared by reload
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Checkout Failed',
                            text: res.message || 'Something went wrong.'
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Checkout error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong during checkout.'
                    });
                }
            });
        });



        // function startChairTimer(chairId, durationInMinutes, timeIn = null) {
        //     const timerId = 'timer_' + chairId;
        //     const boxId = 'chairBox_' + chairId;

        //     const timerElement = document.getElementById(timerId);
        //     const chairBox = document.getElementById(boxId);

        //     if (!timerElement || !chairBox) {
        //         console.error('Timer or chair box not found for ID:', chairId);
        //         return;
        //     }

        // const currentStatus = parseInt(chairBox.getAttribute('data-chair-status'));
        // let endTime;
        // const storageKey = 'chairTimer_' + chairId;
        // const storedEndTime = localStorage.getItem(storageKey);

        // if (storedEndTime) {
        //     endTime = parseInt(storedEndTime);
        // } else {
        //     let startTimestamp;
        //     if (timeIn) {
        //         startTimestamp = new Date(timeIn).getTime();
        //     } else {
        //         startTimestamp = new Date().getTime(); // fallback
        //     }

        //     endTime = startTimestamp + durationInMinutes * 60 * 1000;
        //     localStorage.setItem(storageKey, endTime);
        // }

        // chairBox.style.backgroundColor = 'red';

        // const now = new Date().getTime();
        // const initialDistance = endTime - now;

        // if (initialDistance <= 0 && currentStatus !== 0) {
        //     timerElement.innerText = 'Time Out';
        //     chairBox.classList.add('blinking-chair');
        //     return;
        // }

        //     const countdown = setInterval(() => {
        //         const now = new Date().getTime();
        //         const distance = endTime - now;

        //         if (parseInt(chairBox.getAttribute('data-chair-status')) === 0) {
        //             clearInterval(countdown);
        //             timerElement.innerText = '';
        //             chairBox.classList.remove('blinking-chair');
        //             localStorage.removeItem(storageKey);
        //             return;
        //         }

        //         if (distance <= 0) {
        //             timerElement.innerText = 'Time Out';
        //             chairBox.classList.add('blinking-chair');
        //         } else {
        //             const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        //             const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        //             timerElement.innerText = `${minutes}m ${seconds}s`;
        //         }
        //     }, 1000);
        // }

        function getNextSerialNumber() {
            let rows = document.querySelectorAll('#serviceListBody tr');
            if (rows.length === 0) return 1;

            let lastRow = rows[rows.length - 1];
            let lastSerial = parseInt(lastRow.children[0].textContent);
            return isNaN(lastSerial) ? 1 : lastSerial + 1;
        }

        function reindexSerialNumbers() {
            const rows = document.querySelectorAll('#serviceListBody tr');
            rows.forEach((row, index) => {
                row.children[0].textContent = index + 1;
            });
        }
        document.getElementById('bookBtn').addEventListener('click', function(e) {
            e.preventDefault();

            const formData = {
                mobile: document.getElementById('custPhone').value,
                name: document.getElementById('custName').value,
                email: document.getElementById('custEmail').value,
                chair: document.getElementById('assignedChair').value,
                senior_citizen: document.getElementById('seniorCitizen').value,
                gender: document.getElementById('gender').value,
                staff: document.getElementById('staffSelect').value,
                discount: document.getElementById('discountValue').value,
                discount_type: document.getElementById('discountType').value,
                cash_received: document.getElementById('cashReceivedInput').value,
                services: [],
                products: []
            };

            document.querySelectorAll('#serviceListBody tr').forEach(row => {
                const serviceId = row.getAttribute('data-service-id');
                if (serviceId) {
                    formData.services.push({
                        id: serviceId,
                        name: row.children[1].textContent,
                        service_qnty: parseInt(row.querySelector('.service-qnty').textContent
                            .trim()),
                        duration: row.children[3].textContent,
                        price: row.querySelector('.service-amount').textContent.replace(/[$₹]/g, '')
                    });
                }

                const productId = row.getAttribute('data-product-id');
                if (productId) {
                    formData.products.push({

                        id: productId,
                        name: row.children[1].textContent,
                        product_qnty: row.querySelector('.product-qnty').textContent,
                        price: row.querySelector('.product-amount').textContent.replace(/[$₹]/g, '')
                    });
                }
            });

            fetch('{{ route('branch.book-appointment') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(formData)
                })
                .then(res => res.json())
                .then(response => {
                    if (response.staffavailablestatus) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Staff Not Available',
                            text: 'Staff not available right now. Please wait!',
                        }).then(() => {
                            window.location.reload();
                        });
                        return;
                    }

                    if (response.success && !response.waiting) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Appointment booked successfully!',
                        }).then(() => {
                            window.location.reload();
                        });
                    } else if (response.waiting) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Added to Waiting List',
                            text: 'Chair is occupied. Customer added to waiting list.',
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Booking Failed',
                            text: response.message || 'Something went wrong.',
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Booking Failed',
                        text: 'Please try again later.',
                    });
                });
        });
        document.querySelectorAll('.chair-clickable').forEach(chairBox => {
            chairBox.addEventListener('dblclick', function() {
                const chairId = this.dataset.chairId;

                // If chair is red (occupied)
                if (this.style.backgroundColor === "red" || this.classList.contains('occupied')) {
                    fetch(`/Branch/chair-appointment/${chairId}`)
                        .then(res => res.json())
                        .then(response => {
                            if (response.success) {
                                // Fill form fields
                                document.getElementById('custPhone').value = response.customer.mobile ||
                                    '';
                                document.getElementById('custName').value = response.customer.name ||
                                    '';
                                document.getElementById('custEmail').value = response.customer.email ||
                                    '';
                                document.getElementById('gender').value = response.customer.gender ||
                                    '';
                                document.getElementById('seniorCitizen').value = response.customer
                                    .senior_citizen || '';
                                document.getElementById('assignedChair').value = chairId;
                            document.getElementById('currentAppointmentId').value = response.appointment.id;


                                if (document.getElementById('staffSelect')) {
                                    document.getElementById('staffSelect').value = response
                                        .staff_name || '';
                                }
                                // Clear bill table
                                document.getElementById('serviceListBody').innerHTML = '';

                                // Add services
                                response.services.forEach((service, idx) => {
                                    const srNo = getNextSerialNumber();
                                    const row = document.createElement('tr');

                                    row.setAttribute('data-service-id', service.id);
                                    row.innerHTML = `
                                                                <td style="width: 10%;">${srNo}</td>
                                                                <td style="width: 30%;">${service.service_name}</td>
                                                                <td style="width: 10%;" class="service-qnty">
                                        <span class="qty-value">${service.service_qnty || 1}</span>
                                    </td>
                                                                <td style="width: 15%;">${service.service_duration}</td>
                                                                <td style="width: 15%;" class="service-amount">$${service.service_price}</td>
                                                                <td style="width: 20%;">
                                                                    <button class="btn btn-sm btn-success me-1" onclick="increaseServiceQty(this, ${service.service_price})">+</button>
                                            <button class="btn btn-sm btn-warning me-1" onclick="decreaseServiceQty(this, ${service.service_price})">-</button>
                                                                    </td>
                                    `;
                                    document.getElementById('serviceListBody').appendChild(row);
                                });

                                // Add products
                                response.products.forEach((product, idx) => {
                                    const srNo = getNextSerialNumber();
                                    const row = document.createElement('tr');
                                    row.setAttribute('data-product-id', product.id);
                                    row.innerHTML = `
                                            <td style="width: 10%;">${srNo}</td>
                                            <td style="width: 30%;">${product.product_name}</td>
                                            <td style="width: 10%;" class="product-qnty">
                                                <span class="qty-value">${ product.product_qnty || 1}</span>
                                            </td>
                                            <td style="width: 15%;">-</td>
                                            <td style="width: 15%;" class="product-amount">$${product.product_price}</td>
                                            <td style="width: 20%;">
                                                <button class="btn btn-sm btn-success" onclick="increaseProductQty(this, ${product.product_price})">+</button>
                                                <button class="btn btn-sm btn-warning" onclick="decreaseProductQty(this, ${product.product_price})">-</button>
                                            </td>
                                        `;
                                    document.getElementById('serviceListBody').appendChild(row);
                                });

                                updateBillingSummaryGlobal();
                            } else {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'No Appointment Found',
                                    text: 'There is no active appointment on this chair.'
                                });
                            }
                        }).catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Fetch Error',
                                text: 'Unable to retrieve appointment data.'
                            });
                            console.error(err);
                        });
                } else {
                    // Normal logic for selecting a vacant chair
                    document.getElementById('assignedChair').value = chairId;
                }
            });
        });

        document.getElementById('payBtn').addEventListener('click', function(e) {
            e.preventDefault();

            let formData = {
                services: [],
                products: []
            };
            document.querySelectorAll('#serviceListBody tr').forEach(row => {
                const serviceId = row.getAttribute('data-service-id');
                if (serviceId) {
                    formData.services.push({
                        id: serviceId,
                        name: row.children[1].textContent,
                        service_qnty: parseInt(row.querySelector('.service-qnty').textContent
                            .trim()),
                        duration: row.children[3].textContent,
                        price: row.querySelector('.service-amount').textContent.replace(/[$$]/g, '')
                    });

                }
                const productId = row.getAttribute('data-product-id');

                if (productId) {
                    formData.products.push({
                        id: productId,
                        name: row.children[1].textContent,
                        product_qnty: row.querySelector('.product-qnty').textContent,
                        price: row.querySelector('.product-amount').textContent.replace(/[$$]/g, '')
                    });
                    console.log('Collected product:', {
                        id: productId,
                        name: row.children[1].textContent,
                        product_qnty: row.querySelector('.product-qnty').textContent,
                        price: row.querySelector('.product-amount').textContent
                    });
                }
            });

            fetch('/Branch/pay-bill', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        services: formData.services,
                        products: formData.products,
                        chair_id: document.getElementById('assignedChair').value,
                        mobile: document.getElementById('custPhone').value,
                        bill: {
                            total: document.getElementById('subtotalAmount').textContent.replace(
                                /[$$]/g, ''),
                            discount: document.getElementById('discountValue').value,
                            msf: document.getElementById('msfAmount').textContent.replace(/[$$]/g, ''),
                            final_amount: document.getElementById('totalAmount').textContent.replace(
                                /[$$]/g, ''),
                        }

                    })
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(error => {
                            console.error("Server Response Error:", error.message);
                            throw new Error(error.message);
                        });
                    }
                    return res.json();
                })
                .then(response => {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Successful',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            if (response.bill_id) {
                                window.open(`/Branch/print-bill/${response.bill_id}`, '_blank');
                            }
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Failed',
                            text: response.message
                        });
                        console.error("Error:", response.message);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'An unexpected error occurred!'
                    });
                    console.error("Caught Error:", error.message);
                });
        });
    </script>
    </div>
@endsection
