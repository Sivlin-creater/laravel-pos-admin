<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        h2 {
            text-align: center;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 6px;
        }

        th {
            background-color: #2563EB;
            color: #ffffff;
            text-align: center;
            font-size: 12px;
        }

        td {
            vertical-align: middle;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .status-paid {
            color: #16a34a;
            font-weight: bold;
        }

        .status-partial {
            color: #f59e0b;
            font-weight: bold;
        }

        .status-pending {
            color: #dc2626;
            font-weight: bold;
        }

        .footer {
            margin-top: 10px;
            text-align: right;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>
<body>

<h2>Sales Report</h2>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Payment</th>
            <th>Items</th>
            <th>Total ($)</th>
            <th>Paid ($)</th>
            <th>Remaining ($)</th>
            <th>Discount ($)</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($sales as $sale)
            @php
                $remaining = $sale->total - $sale->paid_amount;
                $status = $sale->total <= $sale->paid_amount
                    ? 'Paid'
                    : ($sale->paid_amount > 0 ? 'Partial' : 'Pending');
            @endphp
            <tr>
                <td class="center">{{ $sale->id }}</td>
                <td>{{ $sale->customer?->name ?? '-' }}</td>
                <td>{{ $sale->paymentmethod?->name ?? '-' }}</td>
                <td>{{ $sale->salesItems
                    ->map(fn ($i) => $i->item?->name)
                    ->filter()
                    ->join(', ') }}</td>
                <td class="right">{{ number_format($sale->total, 2) }}</td>
                <td class="right">{{ number_format($sale->paid_amount, 2) }}</td>
                <td class="right">{{ number_format($remaining, 2) }}</td>
                <td class="right">{{ number_format($sale->discount, 2) }}</td>
                <td class="center
                    {{ $status === 'Paid' ? 'status-paid' : '' }}
                    {{ $status === 'Partial' ? 'status-partial' : '' }}
                    {{ $status === 'Pending' ? 'status-pending' : '' }}">
                    {{ $status }}
                </td>
                <td class="center">{{ $sale->created_at->format('d M Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Generated on {{ now()->format('d M Y H:i') }}
</div>

</body>
</html>