@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome row -->
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
    <div>
        <h1 class="text-3xl lg:text-4xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent mb-2">Dashboard</h1>
        <p class="text-gray-400 text-lg">Welcome back, {{ $user['username'] ?? 'Admin' }}! Here's what's happening with your Fluree data.</p>
    </div>
        <div class="flex items-center space-x-3 mt-4 lg:mt-0">
            <button class="px-6 py-2.5 bg-fluree-blue/90 hover:bg-fluree-blue text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                New Query
            </button>
            <button class="px-6 py-2.5 border border-gray-600 hover:border-gray-500 text-gray-300 hover:text-white font-medium rounded-xl transition-all duration-200">
                Export Report
            </button>
        </div>
</div>

<!-- Stats row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="group bg-gray-800/50 hover:bg-gray-800 border border-gray-700 rounded-2xl p-6 cursor-pointer transition-all duration-200 hover:shadow-2xl hover:-translate-y-1 hover:border-fluree-blue">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400 group-hover:text-fluree-blue transition">Total Records</p>
                <p class="text-3xl font-bold text-white mt-1">{{ count($exampleData ?? []) }}</p>
            </div>
            <div class="w-12 h-12 bg-fluree-blue/20 group-hover:bg-fluree-blue/30 rounded-xl flex items-center justify-center p-3 transition">
                <svg class="w-6 h-6 text-fluree-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex space-x-1 text-sm">
                <span class="text-emerald-400 font-medium">+12%</span>
                <span class="text-gray-500">from last month</span>
            </div>
        </div>
    </div>

    <div class="group bg-gray-800/50 hover:bg-gray-800 border border-gray-700 rounded-2xl p-6 cursor-pointer transition-all duration-200 hover:shadow-2xl hover:-translate-y-1 hover:border-emerald-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400 group-hover:text-emerald-500 transition">Active Sessions</p>
                <p class="text-3xl font-bold text-white mt-1">47</p>
            </div>
            <div class="w-12 h-12 bg-emerald-500/20 group-hover:bg-emerald-500/30 rounded-xl flex items-center justify-center p-3 transition">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex space-x-1 text-sm">
                <span class="text-emerald-400 font-medium">+3</span>
                <span class="text-gray-500">today</span>
            </div>
        </div>
    </div>

    <div class="group bg-gray-800/50 hover:bg-gray-800 border border-gray-700 rounded-2xl p-6 cursor-pointer transition-all duration-200 hover:shadow-2xl hover:-translate-y-1 hover:border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400 group-hover:text-purple-500 transition">Queries Run</p>
                <p class="text-3xl font-bold text-white mt-1">1,234</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/20 group-hover:bg-purple-500/30 rounded-xl flex items-center justify-center p-3 transition">
                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex space-x-1 text-sm">
                <span class="text-emerald-400 font-medium">+156</span>
                <span class="text-gray-500">this week</span>
            </div>
        </div>
    </div>

    <div class="group bg-gray-800/50 hover:bg-gray-800 border border-gray-700 rounded-2xl p-6 cursor-pointer transition-all duration-200 hover:shadow-2xl hover:-translate-y-1 hover:border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400 group-hover:text-orange-500 transition">Storage Used</p>
                <p class="text-3xl font-bold text-white mt-1">2.4 GB</p>
            </div>
            <div class="w-12 h-12 bg-orange-500/20 group-hover:bg-orange-500/30 rounded-xl flex items-center justify-center p-3 transition">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
            </div>
        </div>
        <div class="mt-2 bg-gray-700 rounded-full h-2">
            <div class="bg-gradient-to-r from-orange-500 to-orange-400 h-2 rounded-full" style="width: 67%"></div>
        </div>
    </div>
</div>

<!-- Charts & Main content -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Chart card -->
    <div class="bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-white">Query Trends</h3>
            <button class="text-sm text-gray-400 hover:text-white">Last 30 days</button>
        </div>
        <div class="h-64 bg-gradient-to-r from-gray-900 to-gray-800 rounded-xl p-4 flex items-center justify-center">
            <div class="text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p>Chart placeholder</p>
                <p class="text-sm">ApexCharts / Chart.js integration</p>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-white mb-6">Recent Activity</h3>
        <div class="space-y-4">
            <div class="flex items-start space-x-3 p-3 bg-gray-700/50 rounded-xl hover:bg-gray-700 transition">
                <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white">Query completed</p>
                    <p class="text-xs text-gray-400 mt-1">SELECT * FROM _tx WHERE...</p>
                </div>
                <span class="text-xs text-gray-500 px-2 py-1 bg-gray-600 rounded-full">2 min ago</span>
            </div>
            <div class="flex items-start space-x-3 p-3 bg-gray-700/50 rounded-xl hover:bg-gray-700 transition">
                <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white">User logged in</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $user['username'] ?? 'admin' }}</p>
                </div>
                <span class="text-xs text-gray-500 px-2 py-1 bg-gray-600 rounded-full">5 min ago</span>
            </div>
            <div class="flex items-start space-x-3 p-3 bg-gray-700/50 rounded-xl hover:bg-gray-700 transition">
                <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white">System maintenance</p>
                    <p class="text-xs text-gray-400 mt-1">Fluree node sync completed</p>
                </div>
                <span class="text-xs text-gray-500 px-2 py-1 bg-gray-600 rounded-full">1 hr ago</span>
            </div>
        </div>
        <div class="mt-6 pt-4 border-t border-gray-700">
            <a href="#" class="inline-flex items-center text-sm font-medium text-fluree-blue hover:text-blue-400">
                View all activity
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Fluree Data & Table -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Fluree Data Preview -->
    <div class="lg:col-span-2 bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-white">Fluree Data Preview</h3>
            <div class="flex space-x-2">
                <button class="px-4 py-2 text-xs font-medium bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg transition">JSON</button>
                <button class="px-4 py-2 text-xs font-medium border border-gray-600 hover:border-gray-500 text-gray-300 rounded-lg transition">Table</button>
            </div>
        </div>
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 overflow-x-auto max-h-96">
            <pre class="text-green-400 text-sm font-mono whitespace-pre-wrap">{{ json_encode($exampleData ?? [], JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) }}</pre>
        </div>
    </div>
</div>

<!-- Recent Queries Table -->
<div class="bg-gray-800/50 border border-gray-700 rounded-2xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-700">
        <h3 class="text-lg font-semibold text-white">Recent Queries</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-800/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Query ID</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">#001</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-xs font-medium rounded-full">SELECT</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 text-xs font-medium rounded-full">Success</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">23ms</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">2024-07-20 10:32</td>
                </tr>
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">#002</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-purple-500/20 text-purple-400 text-xs font-medium rounded-full">Transact</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 text-xs font-medium rounded-full">Success</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">89ms</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">2024-07-20 09:45</td>
                </tr>
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">#003</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-orange-500/20 text-orange-400 text-xs font-medium rounded-full">MultiGet</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 text-xs font-medium rounded-full">Pending</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">142ms</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">2024-07-20 08:12</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

