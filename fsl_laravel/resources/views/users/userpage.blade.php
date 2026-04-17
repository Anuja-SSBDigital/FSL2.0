@extends('layouts.app')

@section('title', 'User Page')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">User Page</h1>
            <p class="text-gray-400 mt-1">All Users List</p>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-gray-800/50 border border-gray-700 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-xl font-semibold text-white">All Active Users ({{ count($users) }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table id="usersTable" class="w-full">
                <thead class="bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Mobile</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Designation</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-800/30 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-fluree-blue to-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-semibold text-white">
                                        {{ substr(($user['firstname'] ?? 'U')[0] ?? 'U', 0, 1) }}
                                        {{ substr(($user['lastname'] ?? '')[0] ?? '', 0, 1) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">
                                        {{ $user['firstname'] ?? 'N/A' }} {{ $user['lastname'] ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $user['username'] ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user['username'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user['email'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user['mobileno'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $user['designation'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(($user['isactive'] ?? '0') == '1')
                                <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs font-medium rounded-full">Active</span>
                            @else
                                <span class="px-3 py-1 bg-red-500/20 text-red-400 text-xs font-medium rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-fluree-blue hover:text-blue-400 mr-4">Edit</a>
                            <a href="#" class="text-red-400 hover:text-red-500">Delete</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
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

