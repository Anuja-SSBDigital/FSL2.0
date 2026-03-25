@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Users Management</h1>
            <p class="text-gray-400 mt-1">Manage users, roles, and permissions</p>
        </div>
        <a href="{{ route('users.create') }}" class="bg-fluree-blue hover:bg-fluree-blue/90 text-white px-6 py-2.5 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all">
            Add New User
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-gray-800/50 border border-gray-700 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-xl font-semibold text-white">All Users</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <!-- Sample data - replace with @foreach($users) -->
                    <tr class="hover:bg-gray-800/30 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-fluree-blue/20 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-fluree-blue">JD</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">John Doe</div>
                                    <div class="text-xs text-gray-500">john@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">john@example.com</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 text-xs font-medium rounded-full">Admin</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs font-medium rounded-full">Active</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-fluree-blue hover:text-blue-400 mr-4">Edit</a>
                            <a href="#" class="text-red-400 hover:text-red-500">Delete</a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-800/30 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-purple-500">JS</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">Jane Smith</div>
                                    <div class="text-xs text-gray-500">jane@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">jane@example.com</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-xs font-medium rounded-full">Editor</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 text-xs font-medium rounded-full">Pending</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-fluree-blue hover:text-blue-400 mr-4">Edit</a>
                            <a href="#" class="text-red-400 hover:text-red-500">Delete</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

