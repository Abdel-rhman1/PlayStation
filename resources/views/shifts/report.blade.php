<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Report #{{ $shift->id }}</title>
    <style>
        @page {
            margin: 0;
            size: 80mm auto;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 10mm 5mm;
            background: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
        }

        .meta-section {
            margin-bottom: 20px;
            font-size: 12px;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .divider {
            border-top: 1px solid #000;
            margin: 10px 0;
        }

        .section-title {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .data-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .total-box {
            border: 1px solid #000;
            padding: 8px;
            margin-top: 15px;
            text-align: center;
        }

        .grand-total {
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            font-style: italic;
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #000;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-family: sans-serif;
            font-weight: bold;
            border: none;
            z-index: 100;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <button class="no-print" onclick="window.print()">PRINT REPORT</button>

    <div class="header">
        <h1>SHIFT REPORT</h1>
        <p>{{ config('app.name') }}</p>
    </div>

    <div class="meta-section">
        <div class="meta-row">
            <span>Staff:</span>
            <span>{{ $shift->user->name }}</span>
        </div>
        <div class="meta-row">
            <span>Started:</span>
            <span>{{ $shift->start_time->format('Y-m-d H:i') }}</span>
        </div>
        <div class="meta-row">
            <span>Ended:</span>
            <span>{{ $shift->end_time ? $shift->end_time->format('Y-m-d H:i') : 'IN PROGRESS' }}</span>
        </div>
        <div class="meta-row">
            <span>Duration:</span>
            <span>{{ $shift->end_time ? $shift->end_time->diffForHumans($shift->start_time, true) : '-' }}</span>
        </div>
    </div>

    <div class="section-title">Financial Summary</div>

    <div class="data-row">
        <span>Opening Balance:</span>
        <span>${{ number_format($shift->opening_balance, 2) }}</span>
    </div>

    <div class="divider"></div>

    <div class="data-row">
        <span>Sessions Rev:</span>
        <span>+${{ number_format($summary['sessions_total'], 2) }}</span>
    </div>

    <div class="data-row">
        <span>POS Orders:</span>
        <span>+${{ number_format($summary['orders_total'], 2) }}</span>
    </div>

    <div class="data-row">
        <span>Expenses:</span>
        <span>-${{ number_format($summary['expenses_total'], 2) }}</span>
    </div>

    <div class="total-box">
        <div class="data-row grand-total">
            <span>NET PROFIT:</span>
            <span>${{ number_format($summary['net_total'], 2) }}</span>
        </div>
    </div>

    <div class="divider"></div>
    
    <div class="data-row" style="font-weight: bold;">
        <span>Final Cash Count:</span>
        <span>${{ number_format($shift->closing_balance, 2) }}</span>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>Handover Signature: _______________</p>
    </div>

</body>
</html>
