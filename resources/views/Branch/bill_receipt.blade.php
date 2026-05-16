<!DOCTYPE html>
<html>
<head>
    <title>Receipt - {{ $bill->order_id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            background: #f8f9fa;
        }
        .receipt-box {
            max-width: 380px;
            margin: auto;
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .receipt-title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }
        table {
            width: 100%;
        }
        th, td {
            font-size: 13px;
        }
        .summary-total {
            font-weight: bold;
        }
        .text-end {
            text-align: right;
        }
        @media print {
            .no-print { display: none; }
            body { background: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="text-center mb-2">
            <div class="receipt-title">Salon Receipt</div>
            <div>Order ID: {{ $bill->order_id }}</div>
            <div>Date: {{ $bill->created_at->format('d-m-Y H:i') }}</div>
        </div>

        @php
            $customer = $bill->appointment->customer ?? null;
        @endphp

        @if ($customer)
            <hr>
            <div class="mb-2">
                <strong>Customer Details:</strong><br>
                Name: {{ $customer->name ?? 'N/A' }}<br>
                Mobile: {{ $customer->mobile ?? 'N/A' }}<br>
                Email: {{ $customer->email ?? 'N/A' }}
            </div>
        @endif

        <hr>
        <strong>Items:</strong>
        <table class="table table-bordered mt-1 mb-2">
            <thead class="table-light">
                <tr>
                    <th>Sr</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price $</th>
                </tr>
            </thead>
            <tbody>
                @php $srno = 1; @endphp
                @foreach ($orders as $order)
                    @if ($order->service_name)
                        <tr>
                            <td>{{ $srno++ }}</td>
                            <td>{{ $order->service_name }}</td>
                            <td>{{ $order->service_qnty }}</td>
                            <td>{{ number_format($order->service_price, 2) }}</td>
                        </tr>
                    @endif
                    @if ($order->product_name)
                        <tr>
                            <td>{{ $srno++ }}</td>
                            <td>{{ $order->product_name }}</td>
                            <td>{{ $order->product_qnty }}</td>
                            <td>{{ number_format($order->product_price, 2) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <hr>
        <strong>Summary:</strong>
        <table class="table mt-1">
            <tr>
                <td>Subtotal</td>
                <td class="text-end">${{ number_format($bill->total, 2) }}</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td class="text-end">- ${{ number_format($bill->discount, 2) }}</td>
            </tr>
            <tr>
                <td>Msf</td>
                <td class="text-end">${{ number_format($bill->msf, 2) }}</td>
            </tr>
            <tr class="summary-total">
                <td>Total</td>
                <td class="text-end">${{ number_format($bill->final_amount, 2) }}</td>
            </tr>
        </table>

        <div class="text-end mt-2">
            <strong>Payment:</strong> {{ $bill->payment_type }}
        </div>

        <div class="text-center mt-3 small text-muted">
            Thank you for visiting!
        </div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
