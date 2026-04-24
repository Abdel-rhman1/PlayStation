<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ substr(md5($data['generated_at']), 0, 8) }}</title>
    <style>
        @page {
            margin: 0;
            size: 80mm auto;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 8mm 4mm;
            background: #fff;
            -webkit-print-color-adjust: exact;
        }

        .receipt-card {
            width: 100%;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .brand-logo {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -1px;
            text-transform: uppercase;
            display: inline-block;
            border: 3px solid #000;
            padding: 5px 15px;
            margin-bottom: 5px;
        }

        .brand-sub {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
            opacity: 0.7;
        }

        .meta-info {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            font-weight: 600;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
            display: block;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .detail-row span:first-child {
            color: #666;
            font-weight: 500;
        }

        .detail-row span:last-child {
            font-weight: 700;
            text-align: right;
        }

        .order-table {
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
        }

        .order-table th {
            font-size: 10px;
            text-align: left;
            padding-bottom: 8px;
            text-transform: uppercase;
            color: #999;
        }

        .order-table td {
            padding: 8px 0;
            border-bottom: 0.5px solid #f5f5f5;
        }

        .item-name {
            font-weight: 700;
            display: block;
        }

        .item-meta {
            font-size: 10px;
            color: #888;
        }

        .summary-box {
            background: #fcfcfc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .grand-total {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #000;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .grand-total-label {
            font-size: 14px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .grand-total-amount {
            font-size: 22px;
            font-weight: 900;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #ddd;
        }

        .footer p {
            margin: 0;
            font-size: 10px;
            font-weight: 600;
            color: #444;
        }

        .qr-section {
            margin: 20px auto;
            width: 30mm;
            height: 30mm;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .no-print-bar {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 15px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 10px 25px;
            border-radius: 50px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            z-index: 9999;
        }

        .action-btn {
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 11px;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary { background: #000; color: #fff; }
        .btn-secondary { background: #fff; color: #000; border: 1px solid #eee; }

        @media print {
            .no-print-bar { display: none; }
            body { padding: 4mm; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print-bar">
        <a href="{{ route('dashboard') }}" class="action-btn btn-secondary">← Back</a>
        <button onclick="window.print()" class="action-btn btn-primary">Print Receipt</button>
    </div>

    <div class="receipt-card">
        <div class="brand-header">
            <div class="brand-logo">{{ config('app.name', 'P.STATION') }}</div>
            <div class="brand-sub">Elite Gaming Lounge</div>
        </div>

        <div class="meta-info">
            <span>ID: #{{ substr(md5($data['generated_at']), 0, 6) }}</span>
            <span>{{ date('d M Y, H:i') }}</span>
        </div>

        <div class="session-section">
            <span class="section-title">Session Usage</span>
            <div class="detail-row">
                <span>Machine</span>
                <span>{{ $data['device']['name'] }}</span>
            </div>
            <div class="detail-row">
                <span>Time Period</span>
                <span>{{ date('H:i', strtotime($data['device']['start_time'])) }} - {{ date('H:i', strtotime($data['device']['end_time'])) }}</span>
            </div>
            <div class="detail-row">
                <span>Duration</span>
                <span>{{ $data['device']['duration'] }}</span>
            </div>
            <div class="detail-row">
                <span>Usage Cost</span>
                <span>{{ __('messages.currency_symbol') }} {{ number_format($data['device']['price'], 2) }}</span>
            </div>
        </div>

        @if(!empty($data['orders']['items']))
        <div class="buffet-section" style="margin-top: 25px;">
            <span class="section-title">Cafeteria Items</span>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align: right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['orders']['items'] as $item)
                    <tr>
                        <td>
                            <span class="item-name">{{ $item['product_name'] }}</span>
                            <span class="item-meta">{{ $item['quantity'] }} x {{ __('messages.currency_symbol') }} {{ number_format($item['unit_price'], 2) }}</span>
                        </td>
                        <td style="text-align: right; font-weight: 800;">
                            {{ __('messages.currency_symbol') }} {{ number_format($item['total_price'], 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="summary-box">
            <div class="detail-row">
                <span>Subtotal (Usage)</span>
                <span>{{ __('messages.currency_symbol') }} {{ number_format($data['device']['price'], 2) }}</span>
            </div>
            <div class="detail-row">
                <span>Subtotal (Buffet)</span>
                <span>{{ __('messages.currency_symbol') }} {{ number_format($data['orders']['total'], 2) }}</span>
            </div>
            <div class="grand-total">
                <span class="grand-total-label">Total Payable</span>
                <span class="grand-total-amount">{{ __('messages.currency_symbol') }} {{ number_format($data['grand_total'], 2) }}</span>
            </div>
        </div>

        <div class="qr-section">
            <span style="font-size: 8px; color: #bbb; text-transform: uppercase; font-weight: 900;">Verified Payment</span>
        </div>

        <div class="footer">
            <p>GL HF! WE HOPE TO SEE YOU SOON</p>
            <p style="opacity: 0.5; font-size: 8px; margin-top: 10px;">{{ url('/') }}</p>
        </div>
    </div>

</body>
</html>
