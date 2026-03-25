@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Roles & Permissions</h1>
            <p class="text-gray-400 mt-1">Manage system roles and user permissions</p>
        </div>
        <a href="{{ route('roles.create') }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all">
            Create Role
        </a>
    </div>

    <!-- Roles Table -->
    <div class="bg-gray-800/50 border border-gray-700 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-xl font-semibold text-white">All Roles</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Role Name</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Permissions</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"># Users</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <!-- Sample data - replace with @foreach($roles) -->
                    <tr class="hover:bg-gray-800/30 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-white">Super Admin</div>
                            <div class="text-xs text-gray-500">All permissions</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">read, write, delete, manage-users</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-emerald-400">5</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">2024-01-15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-fluree-blue hover:text-blue-400 mr-4">Edit</a>
                            <a href="#" class="text-red-400 hover:text-red-500">Delete</a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-800/30 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-white">Editor</div>
                            <div class="text-xs text-gray-500">Content editor role</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">read, write</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-400">12</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">2024-02-01</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-fluree-blue hover:text-blue-400 mr-4">Edit</a>
                            <a href="#" class="text-red-400 hover:text-red-500">Delete</a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-800/30 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-white">Viewer</div>
                            <div class="text-xs text-gray-500">Read-only access</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">read</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-purple-400">25</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">2024-03-10</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-fluree-blue hover:text-blue-400 mr-4">Edit</a>
                            <a href="#" class="text-red-400 hover:text-red-500">Delete</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Permissions Legend -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-800/30 border border-gray-700 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Permission Types</h3>
            <div class="space-y-2">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-emerald-400 rounded mr-3"></div>
                    <span class="text-sm text-gray-300">Read</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-400 rounded mr-3"></div>
                    <span class="text-sm text-gray-300">Write</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-orange-400 rounded mr-3"></div>
                    <span class="text-sm text-gray-300">Delete</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

