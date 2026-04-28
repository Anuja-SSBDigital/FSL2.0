@extends('layouts.app')

@section('title', 'Cases')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl lg:text-4xl font-bold text-slate-950 dark:text-white mb-2">Cases</h1>
            @php
                $userRole = $user['role_id']['role'] ?? null;
            @endphp
            @if($userRole === 'SuperAdmin')
                <p class="text-slate-600 dark:text-slate-400 text-lg">All cases in the system</p>
            @else
                <p class="text-slate-600 dark:text-slate-400 text-lg">Cases in your department</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('cases.create') }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-3 font-medium rounded-xl shadow-lg hover:shadow-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Case
            </a>
        </div>
    </div>

    <!-- Admin Filters -->
    @if($userRole === 'SuperAdmin')
        <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900 shadow-lg">
            <h3 class="text-lg font-semibold text-slate-950 dark:text-white mb-4">Filter Cases</h3>
            <form method="GET" action="{{ route('cases.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Department Filter -->
                <div>
                    <label for="department" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Department
                    </label>
                    <select name="department" id="department" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-fluree-blue focus:border-transparent dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept['dept_code'] ?? '' }}" {{ $selectedDept === ($dept['dept_code'] ?? '') ? 'selected' : '' }}>
                                {{ $dept['dept_name'] ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- User Filter -->
                <div>
                    <label for="user" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        User
                    </label>
                    <select name="user" id="user" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-fluree-blue focus:border-transparent dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                        <option value="">-- All Users --</option>
                        @foreach($users as $u)
                            <option value="{{ $u['userid'] ?? '' }}" {{ $selectedUser === ($u['userid'] ?? '') ? 'selected' : '' }}>
                                {{ ($u['firstname'] ?? '') . ' ' . ($u['lastname'] ?? '') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-fluree-blue hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Filter
                    </button>
                    <a href="{{ route('cases.index') }}" class="px-4 py-2 border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium rounded-lg transition dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    @else
        <!-- Status Filter for Non-Admin Users -->
        <div class="rounded-lg border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900 shadow-lg">
            <form method="GET" action="{{ route('cases.index') }}" class="flex items-end gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Show Cases
                    </label>
                    <select name="status" id="status" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-fluree-blue focus:border-transparent dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Cases</option>
                        <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending / Assigned</option>
                        <option value="completed" {{ $statusFilter === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-fluree-blue hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    Filter
                </button>
            </form>
        </div>
    @endif

    <!-- Filters & Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Cases</p>
            <p class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">{{ count($cases) }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Pending / Assigned</p>
            <p class="mt-2 text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                {{ count(array_filter($cases, fn($c) => ($c['status'] ?? '') === 'Pending for Assign' || ($c['status'] ?? '') === 'Assigned')) }}
            </p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">In Progress</p>
            <p class="mt-2 text-2xl font-bold text-blue-600 dark:text-blue-400">
                {{ count(array_filter($cases, fn($c) => ($c['status'] ?? '') === 'In Progress')) }}
            </p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Completed</p>
            <p class="mt-2 text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                {{ count(array_filter($cases, fn($c) => ($c['status'] ?? '') === 'Completed')) }}
            </p>
        </div>
    </div>

    <!-- Cases Table -->
    <div class="rounded-lg border border-slate-200 bg-white shadow-lg dark:border-slate-700 dark:bg-slate-900 overflow-hidden">
        @if(count($cases) > 0)
            <div class="overflow-x-auto">
                <table id="casesTable" class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200 dark:bg-slate-800 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Case Number</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Department</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Agency</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Exhibits</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Created</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($cases as $case)
                            @php
                                $statusColor = [
                                    'Pending for Assign' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
                                    'Assigned' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                                    'In Progress' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200',
                                    'Completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200',
                                ];
                                $status = $case['status'] ?? 'Unknown';
                                $colorClass = $statusColor[$status] ?? 'bg-slate-100 text-slate-800';
                                $caseNum = $case['caseno'] ?? 'N/A';
                                $createdDate = $case['createddate'] ? \Carbon\Carbon::parse($case['createddate'])->format('M d, Y') : 'N/A';
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                <td class="px-6 py-4">
                                    <a href="{{ route('cases.show', $caseNum) }}" class="font-mono font-semibold text-fluree-blue hover:underline dark:text-blue-400">
                                        {{ $caseNum }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-slate-700 dark:text-slate-300">
                                    {{ $case['department_code'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-slate-700 dark:text-slate-300">
                                    {{ $case['agencyname'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 font-semibold">
                                        {{ $case['noof_exhibits'] ?? '0' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $colorClass }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                    {{ $createdDate }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('cases.show', $caseNum) }}" class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-fluree-blue hover:bg-blue-50 rounded-lg transition dark:text-blue-400 dark:hover:bg-blue-900/20">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                        <a href="{{ route('cases.add-details', $caseNum) }}" class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition dark:text-slate-400 dark:hover:bg-slate-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-1">No cases found</h3>
                <p class="text-slate-600 dark:text-slate-400 mb-6">Get started by creating your first case.</p>
                <a href="{{ route('cases.create') }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2 font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Case
                </a>
            </div>
        @endif
    </div>
</div>

@if($userRole === 'SuperAdmin')
<script>
    document.getElementById('department').addEventListener('change', function() {
        const deptCode = this.value;
        const userSelect = document.getElementById('user');

        if (deptCode) {
            // Fetch users for this department
            fetch('{{ route("cases.users-by-department") }}?dept_code=' + encodeURIComponent(deptCode))
                .then(response => response.json())
                .then(users => {
                    // Clear current options except the first one
                    userSelect.innerHTML = '<option value="">-- All Users --</option>';
                    
                    // Add users
                    users.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.userid;
                        option.textContent = user.name;
                        userSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
                    userSelect.innerHTML = '<option value="">-- Error loading users --</option>';
                });
        } else {
            // If no department selected, load all users
            userSelect.innerHTML = '<option value="">-- All Users --</option>';
        }
    });
</script>
@endif

@endsection
