<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Department Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px; vertical-align: top; }
        th { background: #f3f4f6; text-align: left; }
        .muted { color: #6b7280; }
        .footer { margin-top: 18px; font-size: 11px; color:#6b7280; }
    </style>
</head>
<body>
    <h1>Department Report</h1>
    <div class="muted">Generated: {{ now()->format('Y-m-d H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th>Department Name</th>
                <th>Department Code</th>
                <th>Total Cases</th>
            </tr>
        </thead>
        <tbody>
            @forelse($departments as $dept)
                <tr>
                    <td>{{ $dept['dept_name'] ?? 'N/A' }}</td>
                    <td>{{ $dept['dept_code'] ?? 'N/A' }}</td>
                    <td>{{ $dept['case_count'] ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="muted">No departments found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total departments: {{ count($departments) }}
    </div>
</body>
</html>
