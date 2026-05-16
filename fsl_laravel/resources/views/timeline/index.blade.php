@extends('layouts.app')

@section('title', 'Timeline')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-950 dark:text-white">Case Timeline</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-2">View case progress and activities timeline</p>
        </div>
    </div>

    @php
        $totalCases = count($cases);
        $completedCases = count(array_filter($cases, fn($c) => ($c['status'] ?? '') === 'Completed'));
        $activeCases = count(array_filter($cases, fn($c) => ($c['status'] ?? '') !== 'Completed'));
        $pendingCases = count(array_filter($cases, fn($c) => in_array($c['status'] ?? '', ['Pending for Assign', 'Pending'])));
    @endphp

    <!-- Timeline Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900 shadow-lg">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Cases</p>
            <p class="mt-2 text-4xl font-bold text-slate-950 dark:text-white">{{ $totalCases }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900 shadow-lg">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Active Cases</p>
            <p class="mt-2 text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $activeCases }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900 shadow-lg">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Pending</p>
            <p class="mt-2 text-4xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingCases }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900 shadow-lg">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Completed</p>
            <p class="mt-2 text-4xl font-bold text-green-600 dark:text-green-400">{{ $completedCases }}</p>
        </div>
    </div>


    <!-- Cases List -->
    <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-lg font-semibold text-slate-950 dark:text-white">Recent Cases</h2>
        </div>

        <div class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse($cases as $case)
                <a href="{{ route('timeline.case', ['caseno' => $case['caseno'] ?? '']) }}" class="block p-6 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-slate-950 dark:text-white">{{ $case['caseno'] ?? 'N/A' }}</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-sm mt-1">{{ $case['agencyname'] ?? 'N/A' }}</p>

                        </div>
                        <div class="text-right">
                            @php
                                $status = $case['status'] ?? 'Unknown';
                                $statusColor = match($status) {
                                    'Completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                                    'Pending', 'Pending for Assign' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
                                    'Assigned' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                                    default => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200'
                                };
                            @endphp

                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $statusColor }} font-medium text-sm">
                                {{ $status }}
                            </span>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">{{ $case['createddate'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-slate-500 dark:text-slate-400">
                    <p>No cases found</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Timeline View Link -->
    <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg p-6">
        <h2 class="text-lg font-semibold text-slate-950 dark:text-white mb-4">Timeline Options</h2>
        <div class="flex gap-3">
            <a href="{{ route('timeline.statistics') }}" class="inline-flex items-center gap-2 bg-fluree-blue hover:bg-blue-700 text-white px-6 py-2.5 font-medium rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                View Statistics
            </a>
        </div>
    </div>
</div>
@endsection
