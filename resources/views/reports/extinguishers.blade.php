<!DOCTYPE html>
<html>
<head>
    <title>Extinguishers Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #e74c3c; color: white; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        h1 { color: #e74c3c; }
    </style>
</head>
<body>
    <h1>Fire Extinguisher Report</h1>
    <p>Generated: {{ date('Y-m-d H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Serial No.</th><th>Location</th>
                <th>Type</th><th>Size</th><th>Installed</th>
                <th>Expires</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($extinguishers as $e)
            <tr>
                <td>{{ $e->id }}</td>
                <td>{{ $e->serial_number }}</td>
                <td>{{ $e->location }}</td>
                <td>{{ $e->type }}</td>
                <td>{{ $e->size }}</td>
                <td>{{ $e->installation_date }}</td>
                <td>{{ $e->expiry_date }}</td>
                <td>{{ $e->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>