<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Customers Report</title>
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
        }

        td {
            text-align: center;
        }

        .footer {
            margin-top: 10px;
            font-size: 10px;
            text-align: right;
            color: #6b7280;
        }
    </style>
</head>
<body>

<h2>Customers Report</h2>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Registered At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ ucfirst($customer->status ?? 'active') }}</td>
                <td>{{ $customer->created_at->format('d M Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Generated on {{ now()->format('d M Y H:i') }}
</div>

</body>
</html>