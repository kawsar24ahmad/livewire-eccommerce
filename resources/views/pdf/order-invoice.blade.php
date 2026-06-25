```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #2d3748;
            background: #ffffff;
            padding: 30px;
            line-height: 1.6;
        }

        .invoice-wrapper {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .header {
            background: #111827;
            color: white;
            padding: 30px;
        }

        .header-table {
            width: 100%;
        }

        .header-table td {
            border: none;
            vertical-align: top;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .company-tagline {
            font-size: 12px;
            opacity: .8;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .invoice-number {
            font-size: 14px;
        }

        .content {
            padding: 30px;
        }

        .info-grid {
            width: 100%;
            margin-bottom: 30px;
        }

        .info-grid td {
            width: 50%;
            vertical-align: top;
            border: none;
            padding-right: 15px;
        }

        .info-card {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
        }

        .card-title {
            font-size: 11px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .customer-name {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items-table thead {
            background: #f3f4f6;
        }

        .items-table th {
            padding: 12px;
            text-align: left;
            font-size: 12px;
            color: #374151;
            border-bottom: 2px solid #d1d5db;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 380px;
            margin-left: auto;
            margin-top: 30px;
        }

        .summary-table {
            width: 100%;
        }

        .summary-table td {
            border: none;
            padding: 8px 0;
        }

        .summary-table .label {
            color: #6b7280;
        }

        .summary-table .amount {
            text-align: right;
            font-weight: 600;
        }

        .grand-total {
            margin-top: 10px;
            background: #111827;
            color: white;
            padding: 16px 20px;
            border-radius: 10px;
        }

        .grand-total table {
            width: 100%;
        }

        .grand-total td {
            border: none;
        }

        .grand-total .title {
            font-size: 14px;
            font-weight: bold;
        }

        .grand-total .value {
            text-align: right;
            font-size: 24px;
            font-weight: bold;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            background: #dcfce7;
            color: #166534;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #9ca3af;
            font-size: 11px;
        }
    </style>
</head>
<body>

<div class="invoice-wrapper">

    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <div class="company-name">
                        {{ config('app.name') }}
                    </div>

                    <div class="company-tagline">
                        Professional Invoice
                    </div>
                </td>

                <td class="invoice-title">
                    <h1>INVOICE</h1>

                    <div class="invoice-number">
                        Order #{{ $order->id }}
                    </div>

                    <div>
                        {{ $order->created_at->format('d M Y h:i A') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">

        <table class="info-grid">
            <tr>
                <td>
                    <div class="info-card">
                        <div class="card-title">
                            Customer Information
                        </div>

                        <div class="customer-name">
                            {{ $order->customer->name }}
                        </div>

                        <div>{{ $order->customer->email }}</div>
                        <div>{{ $order->shipping_phone }}</div>
                    </div>
                </td>

                <td>
                    <div class="info-card">
                        <div class="card-title">
                            Shipping Address
                        </div>

                        <div>{{ $order->shipping_full_name }}</div>

                        <div>
                            {{ $order->shipping_address_line_1 }}
                            {{ $order->shipping_address_line_2 }}
                        </div>

                        <div>
                            {{ $order->shipping_city }},
                            {{ $order->shipping_state }}
                        </div>

                        <div>
                            {{ $order->shipping_postal_code }}
                        </div>

                        <div>
                            {{ $order->shipping_country }}
                        </div>

                        <br>

                        <span class="status">
                            {{ strtoupper($order->status) }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
            <tr>
                <th>Product</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
            </thead>

            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product->name }}
                    </td>

                    <td class="text-right">
                        {{ $item->quantity }}
                    </td>

                    <td class="text-right">
                        BDT{{ number_format($item->price,2) }}
                    </td>

                    <td class="text-right">
                        BDT{{ number_format($item->price * $item->quantity,2) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="summary">
            <table class="summary-table">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="amount">
                        BDT{{ number_format($order->subtotal,2) }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Discount</td>
                    <td class="amount">
                        - BDT{{ number_format($order->discount_amount,2) }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Shipping</td>
                    <td class="amount">
                        BDT{{ number_format($order->shipping_cost,2) }}
                    </td>
                </tr>
            </table>

            <div class="grand-total">
                <table>
                    <tr>
                        <td class="title">
                            Grand Total
                        </td>

                        <td class="value">
                            BDT{{ number_format($order->total,2) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            Thank you for your purchase. We appreciate your business.
        </div>

    </div>
</div>

</body>
</html>

