<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $order->order_code }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f5f5f5;
            color: #000;
            padding: 20px;
        }
        .receipt-container {
            width: 320px;
            margin: 0 auto;
            background: #fff;
            padding: 16px 14px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border: 1px dashed #ccc;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 12px;
        }
        .shop-name {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .shop-tagline {
            font-size: 11px;
            margin-top: 2px;
            color: #333;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .meta-table, .items-table, .calc-table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 2px 0;
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding-bottom: 4px;
            font-size: 11px;
        }
        .item-name {
            font-weight: bold;
            padding-top: 5px;
        }
        .item-calc {
            display: flex;
            justify-content: space-between;
            padding-bottom: 4px;
        }
        .calc-table td {
            padding: 2px 0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .fw-bold {
            font-weight: bold;
        }
        .receipt-footer {
            text-align: center;
            font-size: 11px;
            margin-top: 12px;
        }
        .actions-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            background-color: #3E2723;
            color: #fff;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            margin: 0 4px;
        }
        .btn-secondary {
            background-color: #6c757d;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .receipt-container {
                width: 100%;
                max-width: 80mm;
                border: none;
                box-shadow: none;
                padding: 0;
                margin: 0;
            }
            .actions-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="actions-bar">
    <button class="btn" onclick="window.print()">
        &#128438; Print the receipt now
    </button>
    <a href="{{ route('pos.index') }}" class="btn btn-secondary">
        &larr; Back to Cashier (POS)
    </a>
</div>

<div class="receipt-container">
    <div class="receipt-header">
        <div class="shop-name">PPKD Cafe's</div>
        <div class="shop-tagline">Coffee &amp; Good Mood</div>
    </div>

    <div class="divider"></div>

    <table class="meta-table">
        <tr>
            <td>Invoice</td>
            <td>: {{ $order->order_code }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ $order->order_date ? $order->order_date->translatedFormat('d F Y H:i') : now()->translatedFormat('d F Y H:i') }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>: {{ $order->user->name ?? 'Kasir' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="items-list">
        @foreach($order->orderDetails as $detail)
            <div class="item-name">{{ $detail->product->product_name ?? 'Produk' }}</div>
            <div class="item-calc">
                <span>{{ $detail->order_quantity }} x Rp{{ number_format($detail->order_price, 0, ',', '.') }}</span>
                <span class="text-right">Rp{{ number_format($detail->order_subtotal, 0, ',', '.') }}</span>
            </div>
        @endforeach
    </div>

    <div class="divider"></div>

    <table class="calc-table">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">Rp{{ number_format($order->order_subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tax (11%)</td>
            <td class="text-right">Rp{{ number_format($order->order_tax, 0, ',', '.') }}</td>
        </tr>
        <tr style="border-top: 1px dashed #333;">
            <td class="fw-bold" style="font-size: 13px; padding-top: 4px;">TOTAL</td>
            <td class="text-right fw-bold" style="font-size: 13px; padding-top: 4px;">Rp{{ number_format($order->order_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Pay</td>
            <td class="text-right">Rp{{ number_format($order->order_paid, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Change</td>
            <td class="text-right">Rp{{ number_format($order->order_change, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Payment</td>
            <td class="text-right" style="text-transform: uppercase;">{{ $order->payment_method }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="receipt-footer">
        <div>Thank You For Your Visit</div>
        <div class="fw-bold" style="margin-top: 4px;">PPKD Cafe's</div>
        {{-- <div style="font-size: 9px; margin-top: 6px; color: #555;">Nikmati Harimu Bersama Secangkir Kopi Pilihan</div> --}}
    </div>
</div>

<script>
    // Auto trigger print when opened directly with print param
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('auto_print') === '1') {
        window.addEventListener('load', () => {
            window.print();
        });
    }
</script>

</body>
</html>
