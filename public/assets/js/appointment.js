let srNo = getNextSerialNumber();

// Utility: Capitalize first letter
function formatCase(value) {
    return (
        (value || "").charAt(0).toUpperCase() +
        (value || "").slice(1).toLowerCase()
    );
}

// Document ready
document.addEventListener('DOMContentLoaded', function () {
    const assignedChairInput = document.getElementById('assignedChair');
    const custPhoneInput = document.getElementById('custPhone');

    // Select chair
    document.querySelectorAll('.chair-clickable').forEach(chairBox => {
        chairBox.addEventListener('click', function () {
            assignedChairInput.value = this.dataset.chairId;
        });
    });

    // Autofill customer details
    custPhoneInput.addEventListener('input', function () {
        const mobile = this.value;
        if (mobile.length === 10) {
            fetch(`/Branch/get-customer-details/${mobile}`)
                .then(res => res.json())
                .then(response => {
                    if (response.status) {
                        const data = response.data;
                        document.getElementById('custName').value = data.name || '';
                        document.getElementById('custEmail').value = data.email || '';
                        document.getElementById('gender').value = (data.gender);
                        document.getElementById('seniorCitizen').value = (data.senior_citizen);
                    }
                });
        }
    });
// Tab toggle handlers
document.getElementById('btnServices').addEventListener('click', () => {
    document.getElementById('servicesContainer').style.display = 'flex';
    document.getElementById('productsContainer').style.display = 'none';
    document.getElementById('pendingBillContainer').style.display = 'none';
    document.getElementById('waitingListContainer').style.display = 'none';
    loadServices();
});

document.getElementById('btnProducts').addEventListener('click', () => {
    document.getElementById('productsContainer').style.display = 'flex';
    document.getElementById('servicesContainer').style.display = 'none';
    document.getElementById('pendingBillContainer').style.display = 'none';
    document.getElementById('waitingListContainer').style.display = 'none';
    loadProducts();
});

document.getElementById('btnPendingBill').addEventListener('click', () => {
    document.getElementById('pendingBillContainer').style.display = 'block';
    document.getElementById('productsContainer').style.display = 'none';
    document.getElementById('servicesContainer').style.display = 'none';
    document.getElementById('waitingListContainer').style.display = 'none';
    loadPendingBills();
});
document.getElementById('btnWaiting').addEventListener('click', () => {
    document.getElementById('waitingListContainer').style.display = 'block';
    document.getElementById('productsContainer').style.display = 'none';
    document.getElementById('servicesContainer').style.display = 'none';
    document.getElementById('pendingBillContainer').style.display = 'none';
    loadWaitingList();            // loads page 1 by default
});


// Load default tab
window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('servicesContainer').style.display = 'flex';
    document.getElementById('productsContainer').style.display = 'none';
    document.getElementById('pendingBillContainer').style.display = 'none';
    document.getElementById('waitingListContainer').style.display = 'none';
    loadServices();
});

function loadServices() {
    fetch("/Branch/branch/get-services")
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('servicesContainer');
            container.innerHTML = data.length ? '' : '<p>No services found.</p>';

            data.forEach(service => {
                container.innerHTML += `
                    <div class="card shadow-sm service-card text-center p-2" 
                         style="width: 160px; cursor: pointer;" 
                         data-id="${service.id}"
                         data-name="${service.service_name}"
                         data-duration="${service.duration}"
                         data-price="${service.price}">
                        <h6 class="mb-1">${service.service_name}</h6>
                        <h6 class="mb-1">${service.gender}</h6>
                     
                        <p class="mb-0 text-success fw-bold">$${service.price}</p>
                    </div>`;
            });

            // Attach event listener for each service card
            document.querySelectorAll('.service-card').forEach(card => {
                card.addEventListener('click', () => {
                    // const chairId = document.getElementById('assignedChair').value;
                    // if (!chairId) return alert("Please select a chair first.");

                    const service = {
                        id: card.dataset.id,
                        name: card.dataset.name,
                        service_qnty: card.dataset.service_qnty || 1, // Default qty to 1 if not specified
                        duration: card.dataset.duration,
                        price: parseFloat(card.dataset.price)
                    };

                    // If service already exists in bill, increase qty
                    const existingRow = [...document.querySelectorAll("#serviceListBody tr")].find(row =>
                        row.dataset.serviceId === service.id
                    );

                if (existingRow) {
    const qtyCell = existingRow.querySelector('.service-qnty .qty-value');
    const amountCell = existingRow.querySelector('.service-amount');

    if (qtyCell && amountCell) {
        let service_qnty = parseInt(qtyCell.textContent);
        service_qnty++;
        qtyCell.textContent = service_qnty;
        amountCell.textContent = `$${(service_qnty * service.price).toFixed(2)}`;
        updateBillingSummaryGlobal();
    }
} else {
    addServiceToBill(service);
}

                });
            });
        });
}


// ========== LOAD PRODUCTS ==========
function loadProducts() {
    fetch("/Branch/branch/get-products")
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('productsContainer');
            container.innerHTML = data.length ? '' : '<p>No products found.</p>';

            data.forEach(product => {
                container.innerHTML += `
                    <div class="card shadow-sm product-card text-center p-2" 
                         style="width: 160px; cursor: pointer;" 
                         data-id="${product.id}" 
                         data-name="${product.product_name}" 
                         data-price="${product.price}" 
                         data-qty ="1">
                        <img src="/uploads/products/${product.product_image}" class="card-img-top" alt="${product.product_name}" style="height: 120px; object-fit: cover;">
                        <div class="card-body text-center p-2">
                            <h6 class="mb-1">${product.product_name}</h6>
                            <p class="mb-0 text-success fw-bold">$${product.price}</p>
                        </div>
                    </div>`;
            });

            // Attach click event to product cards
            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', () => {
                    const product = {
                        id: card.dataset.id,
                        name: card.dataset.name,
                        qty: 1,
                        price: parseFloat(card.dataset.price)
                    };
                    addProductToBill(product);
                });
            });
        });
}


function addServiceToBill(service, chairId) {
    const tbody = document.getElementById("serviceListBody");
    const qty = parseInt(service.service_qnty) || 1;
    const price = parseFloat(service.price);
    const duration = parseInt(service.duration);

    const totalAmount = qty * price;
    const totalDuration = qty * duration;

    const row = document.createElement("tr");
    row.setAttribute('data-service-id', service.id);
    row.setAttribute('data-duration', duration);

    row.innerHTML = `
        <td style="width: 10%;">${srNo++}</td>
        <td style="width: 30%;">${service.name}</td>
        <td style="width: 10%;" class="service-qnty"><span class="qty-value">${qty}</span></td>
        <td style="width: 15%;" class="service-duration">${totalDuration} mins</td>
        <td style="width: 15%;" class="service-amount">₹${totalAmount.toFixed(2)}</td>
        <td style="width: 20%;">
            <button type="button" class="btn btn-sm btn-success me-1"
                onclick="increaseServiceQty(this, ${price.toFixed(2)}, event)">+</button>
            <button type="button" class="btn btn-sm btn-warning me-1"
                onclick="decreaseServiceQty(this, ${price.toFixed(2)}, event)">-</button>
        </td>
    `;

    tbody.appendChild(row);
    updateBillingSummaryGlobal();
}
function addProductToBill(product) {
    const tbody = document.getElementById("serviceListBody");
    const existingRow = document.querySelector(`tr[data-product-id="${product.id}"]`);

    const productQty = parseInt(product.product_qnty) || 1;
    const productPrice = parseFloat(product.price) || 0;

    if (existingRow) {
        const qtySpan = existingRow.querySelector('.product-qnty .qty-value');
        const amountCell = existingRow.querySelector('.product-amount');

        let currentQty = parseInt(qtySpan.textContent) || 1;
        currentQty++;
        qtySpan.textContent = currentQty;
        amountCell.textContent = `$${(currentQty * productPrice).toFixed(2)}`;
    } else {
        const row = document.createElement("tr");
        row.setAttribute('data-product-id', product.id);

       row.innerHTML = `
    <td style="width: 10%;">${srNo++}</td>
    <td style="width: 30%;">${product.name}</td>
    <td style="width: 10%;" class="product-qnty">
        <span class="qty-value">${productQty}</span>
    </td>
    <td style="width: 15%;">-</td>
    <td style="width: 15%;" class="product-amount">$${(productQty * productPrice).toFixed(2)}</td>
    <td style="width: 20%;">
        <button type="button" class="btn btn-sm btn-success" onclick="increaseProductQty(this, ${productPrice})">+</button>
        <button type="button" class="btn btn-sm btn-warning" onclick="decreaseProductQty(this, ${productPrice})">-</button>
    </td>`;
        tbody.appendChild(row);
    }

    updateBillingSummaryGlobal();
}


$('#btnPendingBill').click(function () {
    $('#servicesContainer').hide();
    $('#productsContainer').hide();
    $('#pendingBillContainer').show();
    loadPendingBills();
});

function loadPendingBills() {
    $.get('/pending-bills', function(bills) {
        if (!bills.length) {
            $('#pendingBillContainer').html('<p>No pending bills.</p>');
            return;
        }

        let html = `
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Mobile</th>
                        <th>Staff</th>
                        <th>Services</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
        `;

        $.each(bills, function(i, bill) {
            html += `
                <tr>
                    <td>${i + 1}</td>
                    <td>${bill.mobile}</td>
                    <td>${bill.staff_name || '—'}</td>
                    <td>${bill.services || '—'}</td>
                    <td>${bill.products || '—'}</td>
                    <td>${bill.status || 'pending'}</td>
                    <td>
                        <button 
                            class="btn btn-sm btn-danger pending-pay-btn" 
                            data-mobile="${bill.mobile}"
                            data-appointment-id="${bill.appointment_id}">
                            Pay Bill
                        </button>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        $('#pendingBillContainer').html(html);
    }).fail(function() {
        $('#pendingBillContainer').html('<p class="text-danger">Failed to load pending bills.</p>');
    });
}


// ========== UPDATE CHAIR STATUS ==========
function updateChairStatus(chairId, status) {
    fetch('/update-chair-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ chair_id: chairId, status })
    })
    .then(res => res.json())
    .then(data => console.log(data.message))
    .catch(err => console.error(err));
}

});
let currentWaitingPage = 1;   // keep track globally

function loadWaitingList(page = 1) {
    fetch(`/Branch/branch/get-waiting-list?page=${page}`)
      .then(res => res.json())
      .then(res => {
          currentWaitingPage = res.current_page;

          const tbody = document.getElementById('waitingListBody');
          tbody.innerHTML = '';

          if (!res.data.length) {
              tbody.innerHTML =
                `<tr><td colspan="8" class="text-center text-muted">No customers in waiting list.</td></tr>`;
          } else {
              // build chair & staff option lists once
              const chairOpts = chairs.map(c =>
                `<option value="${c.chair_id}">Chair ${c.chair_id}</option>`).join('');
              const staffOpts = staffs.map(s =>
                `<option value="${s.name}">${s.name}</option>`).join('');

              res.data.forEach((item, idx) => {
                  const tr = document.createElement('tr');
                  tr.innerHTML = `
                      <td>${(res.from ?? 1) + idx}</td>
                      <td>${item.mobile}</td>
                      <td>${item.name ?? '-'}</td>
                      <td>
                        ${item.chair_id|| '-'}
                      </td>
                      <td>
                         ${item.staff_name ? item.staff_name : '-'}
                      </td>
                   
                    <td>${new Date(item.created_at).toLocaleString()}</td>

                      <td>
                          <button class="btn btn-sm btn-success checkin-btn" data-mobile="${item.mobile}">
                              Check‑in
                          </button>
                            <button class="btn btn-sm btn-danger cancel-btn" data-id="${item.id}">
        Cancel
    </button>
                      </td>`;
                  tbody.appendChild(tr);
              });
          }

          renderWaitingPagination(res);
      });
}

function renderWaitingPagination(res) {
    const ul = document.getElementById('waitingPagination');
    ul.innerHTML = '';

    // Prev
    ul.appendChild(makePageLi('«', res.current_page - 1, res.current_page === 1));

    // Page numbers
    for (let p = 1; p <= res.last_page; p++) {
        ul.appendChild(makePageLi(p, p, p === res.current_page));
    }

    // Next
    ul.appendChild(makePageLi('»', res.current_page + 1, res.current_page === res.last_page));
}

function makePageLi(label, targetPage, disabled) {
    const li = document.createElement('li');
    li.className = `page-item ${disabled ? 'disabled' : ''}`;
    li.innerHTML = `<a class="page-link" style="cursor:pointer;">${label}</a>`;
    if (!disabled) {
        li.addEventListener('click', () => loadWaitingList(targetPage));
    }
    return li;
}
// ========== QUANTITY FUNCTIONS ==========
function increaseProductQty(btn, price) {
    const row = btn.closest('tr');
    const qtySpan = row.querySelector('.product-qnty .qty-value');
    const amountCell = row.querySelector('.product-amount');

    let currentQty = parseInt(qtySpan.textContent) || 1;
    currentQty++;
    qtySpan.textContent = currentQty;
    amountCell.textContent = `$${(currentQty * price).toFixed(2)}`;

    updateBillingSummaryGlobal();
}

function decreaseProductQty(btn, price) {
    const row = btn.closest('tr');
    const qtySpan = row.querySelector('.product-qnty .qty-value');
    let currentQty = parseInt(qtySpan.textContent) || 1;

    if (currentQty > 1) {
        currentQty--;
        qtySpan.textContent = currentQty;
        row.querySelector('.product-amount').textContent = `$${(currentQty * price).toFixed(2)}`;
    } else {
        row.remove(); // Or call removeRow(btn) if defined
    }

    updateBillingSummaryGlobal();
}

function increaseServiceQty(btn, price, event) {
    if (event) event.preventDefault();

    const row = btn.closest('tr');
    const qtyCell = row.querySelector('.qty-value');
    let service_qnty = parseInt(qtyCell.textContent);
    service_qnty++;
    qtyCell.textContent = service_qnty;

    const baseDuration = parseInt(row.getAttribute('data-duration'));
    row.querySelector('.service-duration').textContent = `${service_qnty * baseDuration} mins`;

    row.querySelector('.service-amount').textContent = `$${(service_qnty * price).toFixed(2)}`;

    updateBillingSummaryGlobal();
}

function decreaseServiceQty(btn, price, event) {
    if (event) event.preventDefault();

    const row = btn.closest('tr');
    const qtyCell = row.querySelector('.qty-value');
    let service_qnty = parseInt(qtyCell.textContent);

    if (service_qnty > 1) {
        service_qnty--;
        qtyCell.textContent = service_qnty;

        const baseDuration = parseInt(row.getAttribute('data-duration'));
        row.querySelector('.service-duration').textContent = `${service_qnty * baseDuration} mins`;

        row.querySelector('.service-amount').textContent = `$${(service_qnty * price).toFixed(2)}`;
    } else {
        removeRow(btn);
    }

    updateBillingSummaryGlobal();
}


function removeRow(btn) {
    btn.closest('tr').remove();
    updateBillingSummaryGlobal();
}

// ========== REMOVE SERVICE ==========
function removeService(btn, chairId) {
    btn.closest("tr").remove();
    updateChairStatus(chairId, 0);
    const box = document.querySelector(`[data-chair-id="${chairId}"]`);
    if (box) box.style.backgroundColor = "green";
    updateBillingSummaryGlobal();
}

function updateBillingSummaryGlobal() {
    let subtotal = 0;

    document.querySelectorAll("#serviceListBody tr, #productListBody tr").forEach(row => {
        const amountCell = row.querySelector('.product-amount') || row.querySelector('.service-amount') || row.children[4];
        const amountText = amountCell.textContent || '0';
        subtotal += parseFloat(amountText.replace(/[^0-9.]/g, '')) || 0;
    });

    document.getElementById('subtotalAmount').textContent = `$${subtotal.toFixed(2)}`;

    const discountValue = parseFloat(document.getElementById('discountValue').value) || 0;
    const discountType = document.getElementById('discountType').value;
    const discountAmt = discountType === 'percent'
        ? (subtotal * discountValue / 100)
        : discountValue;

    const subtotalAfterDiscount = subtotal - discountAmt;
    const msfAmt = subtotalAfterDiscount * 0.011;
    const total = subtotalAfterDiscount + msfAmt;

    document.getElementById('msfAmount').textContent = `$${msfAmt.toFixed(2)}`;
    document.getElementById('totalAmount').textContent = `$${total.toFixed(2)}`;

    const cashReceived = parseFloat(document.getElementById('cashReceivedInput').value) || 0;
    const changeAmt = cashReceived - total;

    document.getElementById('changeAmount').textContent = `$${(changeAmt > 0 ? changeAmt : 0).toFixed(2)}`;
}