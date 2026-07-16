<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Report</title>
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
    <h1>Project Report</h1>
    <div class="muted">Generated: {{ now()->format('Y-m-d H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th>Case No</th>
                <th>Agency Name</th>
                <th>Department</th>
                <th>Status</th>
                <th>Entered By</th>
                <th>Created Date</th>
                <th>Updated Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cases as $case)
                <tr>
                    <td>{{ $case['caseno'] ?? 'N/A' }}</td>
                    <td>{{ $case['agencyname'] ?? 'N/A' }}</td>
                    <td>{{ $case['department_code'] ?? 'N/A' }}</td>
                    <td>{{ ucfirst(strtolower($case['status'] ?? 'unknown')) }}</td>
                    <td>{{ $case['enteredby'] ?? 'N/A' }}</td>
                    <td>{{ $case['createddate'] ?? 'N/A' }}</td>
                    <td>{{ $case['updateddate'] ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="muted">No cases found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total cases: {{ $statusCounts['total'] ?? 0 }} |
        Pending: {{ $statusCounts['pending'] ?? 0 }} |
        In Progress: {{ $statusCounts['in_progress'] ?? 0 }} |
        Completed: {{ $statusCounts['completed'] ?? 0 }} |
        Completion Rate: {{ $completionRate ?? 0 }}%
    </div>
</body>
</html>
