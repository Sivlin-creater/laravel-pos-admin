<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f3f3f3; }
        h2 { text-align: center;}
    </style>
</head>
<body>
<h2>Items List</h2>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>SKU</th>
        <th>Original Price</th>
        <th>Selling Price</th>
        <th>Quantity</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->sku }}</td>
            <td>${{ number_format($item->original_price, 2) }}</td>
            <td>${{ number_format($item->selling_price, 2) }}</td>
            <td>{{ $item->inventory?->quantity ?? 0 }}</td>
            <td>{{ ucfirst($item->status) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>