@extends('layouts.app')

@section('title', 'Timeline Statistics')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-950 dark:text-white">Timeline Statistics</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-2">Case progress and activity statistics</p>
        </div>
        <a href="{{ route('timeline.index') }}" class="text-fluree-blue hover:text-blue-700 font-medium">← Back to Timeline</a>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg p-6">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Cases</p>
            <p class="mt-2 text-4xl font-bold text-slate-950 dark:text-white">{{ $stats['total_cases'] ?? 0 }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg p-6">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Active Cases</p>
            <p class="mt-2 text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['active_cases'] ?? 0 }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg p-6">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Pending Cases</p>
            <p class="mt-2 text-4xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending_cases'] ?? 0 }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg p-6">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Completed Cases</p>
            <p class="mt-2 text-4xl font-bold text-green-600 dark:text-green-400">{{ $stats['completed_cases'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg p-6">
        <h2 class="text-lg font-semibold text-slate-950 dark:text-white mb-6">Performance Metrics</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Completion Rate -->
            <div>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-4">Case Completion Rate</p>
                @php
                    $total = $stats['total_cases'] ?? 1;
                    $completed = $stats['completed_cases'] ?? 0;
                    $rate = $total > 0 ? round(($completed / $total) * 100) : 0;
                @endphp
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full" style="width: {{ $rate }}%"></div>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">{{ $rate }}% Complete</p>
            </div>

            <!-- Active Rate -->
            <div>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-4">Active Cases Rate</p>
                @php
                    $activeRate = $total > 0 ? round((($stats['active_cases'] ?? 0) / $total) * 100) : 0;
                @endphp
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $activeRate }}%"></div>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">{{ $activeRate }}% Active</p>
            </div>
        </div>
    </div>

    <!-- Timeline Chart (Placeholder) -->
    <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg p-6">
        <h2 class="text-lg font-semibold text-slate-950 dark:text-white mb-4">Case Distribution</h2>
        <div class="flex items-center justify-center h-64 bg-slate-50 dark:bg-slate-800 rounded-lg">
            <p class="text-slate-500 dark:text-slate-400">Chart visualization will be displayed here</p>
        </div>
    </div>
</div>
@endsection
