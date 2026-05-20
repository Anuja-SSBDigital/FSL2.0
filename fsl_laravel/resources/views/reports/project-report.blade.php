@extends('layouts.app')

@section('title', 'Project Report')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-950 dark:text-white">Project Report</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-2">Evidence acceptance project overview and completion status</p>
        </div>
        <div class="flex gap-2">
            <form method="POST" action="{{ route('reports.download') }}">
                @csrf
                <input type="hidden" name="report_type" value="project" />
                <button type="submit" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 font-medium rounded-lg transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                    </svg>
                    PDF
                </button>
            </form>

            <button class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 font-medium rounded-lg transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                </svg>
                CSV
            </button>
        </div>
    </div>

    <!-- Project Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Cases -->
        <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg overflow-hidden">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 px-6 py-4">
                <h3 class="text-white font-semibold text-sm">Total Cases</h3>
            </div>
            <div class="p-6">
                <p class="text-3xl font-bold text-slate-950 dark:text-white">{{ $statusCounts['total'] }}</p>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Cases in the project</p>
            </div>
        </div>

        <!-- Pending Cases -->
        <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg overflow-hidden">
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 px-6 py-4">
                <h3 class="text-white font-semibold text-sm">Pending</h3>
            </div>
            <div class="p-6">
                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $statusCounts['pending'] }}</p>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Not yet started</p>
            </div>
        </div>

        <!-- In Progress -->
        <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg overflow-hidden">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 px-6 py-4">
                <h3 class="text-white font-semibold text-sm">In Progress</h3>
            </div>
            <div class="p-6">
                <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $statusCounts['in_progress'] }}</p>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Currently processing</p>
            </div>
        </div>

        <!-- Completed Cases -->
        <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg overflow-hidden">
            <div class="bg-gradient-to-br from-green-500 to-green-600 px-6 py-4">
                <h3 class="text-white font-semibold text-sm">Completed</h3>
            </div>
            <div class="p-6">
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $statusCounts['completed'] }}</p>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Successfully completed</p>
            </div>
        </div>
    </div>

    <!-- Completion Progress -->
    <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-lg font-semibold text-slate-950 dark:text-white">Project Completion</h2>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Completion Rate</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $completionRate }}%</p>
            </div>
            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 h-full rounded-full transition-all duration-500" 
                     style="width: {{ $completionRate }}%"></div>
            </div>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-4">
                {{ $statusCounts['completed'] }} out of {{ $statusCounts['total'] }} cases completed
            </p>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-lg font-semibold text-slate-950 dark:text-white">Status Distribution</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-6">
            <!-- Pending Bar -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <p class="font-medium text-slate-700 dark:text-slate-300">Pending</p>
                    <p class="text-sm font-bold text-yellow-600 dark:text-yellow-400">
                        @if($statusCounts['total'] > 0)
                            {{ round(($statusCounts['pending'] / $statusCounts['total']) * 100) }}%
                        @else
                            0%
                        @endif
                    </p>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                    <div class="bg-yellow-500 h-full rounded-full transition-all duration-500" 
                         style="width: @if($statusCounts['total'] > 0){{ (($statusCounts['pending'] / $statusCounts['total']) * 100) }}@else0@endif%"></div>
                </div>
            </div>

            <!-- In Progress Bar -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <p class="font-medium text-slate-700 dark:text-slate-300">In Progress</p>
                    <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                        @if($statusCounts['total'] > 0)
                            {{ round(($statusCounts['in_progress'] / $statusCounts['total']) * 100) }}%
                        @else
                            0%
                        @endif
                    </p>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                    <div class="bg-indigo-500 h-full rounded-full transition-all duration-500" 
                         style="width: @if($statusCounts['total'] > 0){{ (($statusCounts['in_progress'] / $statusCounts['total']) * 100) }}@else0@endif%"></div>
                </div>
            </div>

            <!-- Completed Bar -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <p class="font-medium text-slate-700 dark:text-slate-300">Completed</p>
                    <p class="text-sm font-bold text-green-600 dark:text-green-400">
                        @if($statusCounts['total'] > 0)
                            {{ round(($statusCounts['completed'] / $statusCounts['total']) * 100) }}%
                        @else
                            0%
                        @endif
                    </p>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                    <div class="bg-green-500 h-full rounded-full transition-all duration-500" 
                         style="width: @if($statusCounts['total'] > 0){{ (($statusCounts['completed'] / $statusCounts['total']) * 100) }}@else0@endif%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cases by Status Table -->
    <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-lg font-semibold text-slate-950 dark:text-white">All Cases Details</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200 dark:bg-slate-800 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Case No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Agency Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Department</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Entered By</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Created Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">Updated Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($cases as $case)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                            <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $case['caseno'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $case['agencyname'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $case['department_code'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $status = $case['status'] ?? 'Unknown';
                                    $statusClass = match(strtolower($status)) {
                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
                                        'in_progress', 'assigned' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200',
                                        default => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $statusClass }} font-medium text-sm">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $case['enteredby'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-sm">
                                {{ $case['createddate'] ? \Carbon\Carbon::parse($case['createddate'])->format('Y-m-d H:i') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-sm">
                                {{ $case['updateddate'] ? \Carbon\Carbon::parse($case['updateddate'])->format('Y-m-d H:i') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-600 dark:text-slate-400">
                                <svg class="w-16 h-16 mx-auto mb-3 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">No cases found</p>
                                <p class="text-sm">No case data is available at the moment.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
