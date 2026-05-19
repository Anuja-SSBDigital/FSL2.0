@extends('layouts.app')

@section('title', 'User Page')

@section('content')
<div class="space-y-8">
    <!-- Current User Profile Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-8 dark:border-slate-700 dark:from-slate-900 dark:to-slate-950 shadow-lg">
                <div class="flex flex-col items-center">
                    <!-- Avatar -->
                    <div class="w-24 h-24 bg-gradient-to-r from-fluree-blue to-blue-500 rounded-full flex items-center justify-center text-4xl font-bold text-white shadow-lg mb-4">
                        {{ strtoupper(substr(($currentUser['firstname'] ?? 'U')[0] ?? 'U', 0, 1) . substr(($currentUser['lastname'] ?? '')[0] ?? '', 0, 1)) }}
                    </div>
                    <!-- Name -->
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white text-center">
                        {{ $currentUser['firstname'] ?? '' }} {{ $currentUser['lastname'] ?? '' }}
                    </h2>
                    <!-- Username -->
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">@{{ $currentUser['username'] ?? 'N/A' }}</p>
                    
                    <!-- Role Badge -->
                    <div class="mt-4 px-4 py-2 rounded-full bg-fluree-blue/20 text-fluree-blue dark:bg-fluree-blue/10">
                        <span class="font-semibold text-sm">{{ $currentUser['role_id']['role'] ?? 'User' }}</span>
                    </div>

                    <!-- Status -->
                    <div class="mt-4">
                        @if(($currentUser['isactive'] ?? '0') == '1')
                            <span class="px-3 py-1 bg-green-500/20 text-green-600 dark:text-green-400 text-xs font-semibold rounded-full">● Active</span>
                        @else
                            <span class="px-3 py-1 bg-red-500/20 text-red-600 dark:text-red-400 text-xs font-semibold rounded-full">● Inactive</span>
                        @endif
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-slate-200 dark:border-slate-700 mt-6 pt-6">
                    <!-- Contact Info -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold">Email</p>
                            <p class="text-sm text-slate-900 dark:text-slate-100 font-medium mt-1 break-all">
                                {{ $currentUser['email'] ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold">Mobile</p>
                            <p class="text-sm text-slate-900 dark:text-slate-100 font-medium mt-1">
                                {{ $currentUser['mobileno'] ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold">Designation</p>
                            <p class="text-sm text-slate-900 dark:text-slate-100 font-medium mt-1">
                                {{ $currentUser['designation'] ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Button -->
                <button class="w-full mt-6 bg-fluree-blue hover:bg-blue-700 text-white font-semibold py-3 rounded-2xl transition-all shadow-lg hover:shadow-xl">
                    Edit Profile
                </button>
            </div>
        </div>

        <!-- Profile Details Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Department Info -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-950 shadow-lg">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Department Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold">Department</p>
                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100 mt-1">
                            {{ $currentUser['dept_code']['dept_name'] ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold">Department Code</p>
                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100 mt-1">
                            {{ $currentUser['dept_code']['dept_code'] ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold">Institution</p>
                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100 mt-1">
                            {{ $currentUser['inst_id']['inst_name'] ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold">Institution Code</p>
                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100 mt-1">
                            {{ $currentUser['inst_id']['inst_code'] ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/20 dark:bg-emerald-950/10">
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 uppercase tracking-wider font-semibold">Role</p>
                    <p class="text-xl font-bold text-emerald-900 dark:text-emerald-100 mt-2">{{ $currentUser['role_id']['role'] ?? 'User' }}</p>
                </div>
                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-900/20 dark:bg-blue-950/10">
                    <p class="text-xs text-blue-600 dark:text-blue-400 uppercase tracking-wider font-semibold">Status</p>
                    <p class="text-xl font-bold text-blue-900 dark:text-blue-100 mt-2">
                        @if(($currentUser['isactive'] ?? '0') == '1') Active @else Inactive @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Directory Section -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold text-slate-950 dark:text-white">Users Directory</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">All Active Users ({{ count($users) }})</p>
            </div>
        </div>

        <!-- Users Table -->
        <div class="rounded-3xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-950 overflow-hidden shadow-xl">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Team Members</h3>
            </div>
        <div class="overflow-x-auto">
            <table id="usersTable" class="w-full">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Mobile</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Designation</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-fluree-blue to-blue-500 rounded-full flex items-center justify-center text-xs font-semibold text-white">
                                    <span>{{ substr(($user['firstname'] ?? 'U')[0] ?? 'U', 0, 1) }}{{ substr(($user['lastname'] ?? '')[0] ?? '', 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-slate-900 dark:text-white">
                                        {{ $user['firstname'] ?? 'N/A' }} {{ $user['lastname'] ?? '' }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $user['username'] ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">{{ $user['username'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">{{ $user['email'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">{{ $user['mobileno'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">{{ $user['designation'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(($user['isactive'] ?? '0') == '1')
                                <span class="px-3 py-1 bg-green-500/20 text-green-600 dark:text-green-400 text-xs font-semibold rounded-full">Active</span>
                            @else
                                <span class="px-3 py-1 bg-red-500/20 text-red-600 dark:text-red-400 text-xs font-semibold rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-fluree-blue hover:text-blue-700 dark:hover:text-blue-400 transition mr-4">Edit</a>
                            <a href="#" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 transition">Delete</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            No active users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#usersTable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        responsive: true,
        dom: '<"flex flex-col md:flex-row md:justify-between md:items-center mb-4"lf>rt<"flex flex-col md:flex-row md:justify-between md:items-center mt-4"ip>',
    });
});
</script>
@endsection

