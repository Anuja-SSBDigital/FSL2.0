<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Performance Report</title>
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
    <h1>User Performance Report</h1>
    <div class="muted">Generated: {{ now()->format('Y-m-d H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Department</th>
                <th>Assigned Count</th>
                <th>Entered Count</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ trim(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '')) }}</td>
                    <td>{{ $user['email'] ?? 'N/A' }}</td>
                    <td>{{ $user['username'] ?? 'N/A' }}</td>
                    <td>
                        @if(is_array($user['dept_id'] ?? null))
                            {{ $user['dept_id']['dept_name'] ?? 'N/A' }}
                        @else
                            {{ $user['dept_id'] ?? 'N/A' }}
                        @endif
                    </td>
                    <td>{{ $user['assigned_count'] ?? 0 }}</td>
                    <td>{{ $user['entered_count'] ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="muted">No users found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total users: {{ count($users) }}
    </div>
</body>
</html>
