<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inventories List</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center; /* Center all text */
            vertical-align: middle;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-in-stock {
            color: green;
            font-weight: bold;
        }
        .status-low-stock {
            color: orange;
            font-weight: bold;
        }
        .status-out-of-stock {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Inventories List</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Stock Quantity</th>
                <th>Stock Status</th>
                <th>Added On</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventories as $inventory)
                <tr>
                    <td>{{ $inventory->id }}</td>
                    <td>{{ $inventory->item->name ?? '-' }}</td>
                    <td>{{ $inventory->quantity }}</td>
                    <td>
                        @if($inventory->stock_status === 'In Stock')
                            <span class="status-in-stock">In Stock</span>
                        @elseif($inventory->stock_status === 'Low Stock')
                            <span class="status-low-stock">Low Stock</span>
                        @elseif($inventory->stock_status === 'Out of Stock')
                            <span class="status-out-of-stock">Out of Stock</span>
                        @else
                            {{ $inventory->stock_status }}
                        @endif
                    </td>
                    <td>{{ $inventory->created_at->format('d M Y, H:i') }}</td>
                    <td>{{ $inventory->updated_at->format('d M Y, H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>