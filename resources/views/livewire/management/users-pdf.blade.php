<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 0 20px; }
        header { text-align: center; margin-bottom: 15px; }
        header img { height: 50px; }
        h1 { margin: 5px 0; font-size: 18px; font-weight: bold; }
        .meta { text-align: right; font-size: 10px; color: #555; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #007bff; color: #fff; font-weight: bold; text-align: center; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        td.center { text-align: center; }
        footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #888; }
        .pagenum:before { content: counter(page); }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('logo.png') }}" alt="Logo">
        <h1>Users Report</h1>
    </header>

    <div class="meta">
        Generated on: {{ now()->format('d M Y, H:i') }} <br>
        Exported by: {{ auth()->user()->name ?? 'Admin' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="center">{{ ucfirst($user->role) }}</td>
                    <td class="center">{{ ucfirst($user->status) }}</td>
                    <td class="center">{{ $user->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Page <span class="pagenum"></span> | User Management System
    </footer>
</body>
</html>