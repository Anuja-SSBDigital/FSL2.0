@extends('layouts.app')

@section('title', 'Case Timeline - ' . ($case['caseno'] ?? 'Case'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-950 dark:text-white">Case Timeline</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-2">{{ $case['caseno'] ?? 'Case' }} - {{ $case['agencyname'] ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('timeline.index') }}" class="inline-flex items-center gap-2 bg-slate-200 hover:bg-slate-300 text-slate-900 px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back
        </a>
    </div>

    <!-- Case Details Card -->
    <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <p class="text-xs uppercase tracking-wide font-semibold text-slate-600 dark:text-slate-400">Case Number</p>
                <p class="mt-2 text-lg font-bold text-slate-950 dark:text-white">{{ $case['caseno'] ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide font-semibold text-slate-600 dark:text-slate-400">Agency</p>
                <p class="mt-2 text-lg font-bold text-slate-950 dark:text-white">{{ $case['agencyname'] ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide font-semibold text-slate-600 dark:text-slate-400">Status</p>
                <p class="mt-2">
                    @php
                        $status = $case['status'] ?? 'Unknown';
                        $statusColor = match($status) {
                            'Completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                            'Pending', 'Pending for Assign' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
                            'Assigned' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                            default => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200'
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full {{ $statusColor }} font-medium text-sm">
                        {{ $status }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide font-semibold text-slate-600 dark:text-slate-400">Created Date</p>
                <p class="mt-2 text-lg font-bold text-slate-950 dark:text-white">{{ $case['createddate'] ?? 'N/A' }}</p>
            </div>
        </div>

        @if($case['notes'] ?? null)
            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                <p class="text-xs uppercase tracking-wide font-semibold text-slate-600 dark:text-slate-400">Notes</p>
                <p class="mt-2 text-slate-700 dark:text-slate-300">{{ $case['notes'] }}</p>
            </div>
        @endif
    </div>

    <!-- Timeline -->
    <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-lg font-semibold text-slate-950 dark:text-white">Activity Timeline</h2>
        </div>

        <div class="p-6">
            @if($activities && count($activities) > 0)
                <div class="relative">
                    <!-- Timeline line -->
                    <div class="absolute left-7 top-0 bottom-0 w-0.5 bg-slate-200 dark:bg-slate-700"></div>

                    <!-- Timeline items -->
                    <div class="space-y-6">
                        @foreach($activities as $activity)
                            <div class="relative pl-20">
                                <!-- Timeline dot -->
                                <div class="absolute left-2 top-1.5 w-10 h-10 bg-fluree-blue rounded-full flex items-center justify-center border-4 border-white dark:border-slate-900">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>

                                <!-- Activity content -->
                                <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-slate-950 dark:text-white">
                                                @php
                                                    $status = $activity['status'] ?? 'Unknown Activity';
                                                    echo ucfirst($status);
                                                @endphp
                                            </h4>
                                            @if($activity['notes'] ?? null)
                                                <p class="text-slate-600 dark:text-slate-400 text-sm mt-1">{{ $activity['notes'] }}</p>
                                            @endif
                                        </div>
                                        <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap ml-4">
                                            {{ $activity['createddate'] ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <!-- Activity details -->
                                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                        @if($activity['assignto'] ?? null)
                                            <div>
                                                <span class="text-slate-600 dark:text-slate-400">Assigned to:</span>
                                                <span class="ml-2 font-medium text-slate-900 dark:text-white">{{ $activity['assignto'] }}</span>
                                            </div>
                                        @endif
                                        @if($activity['caseassignby'] ?? null)
                                            <div>
                                                <span class="text-slate-600 dark:text-slate-400">Assigned by:</span>
                                                <span class="ml-2 font-medium text-slate-900 dark:text-white">{{ $activity['caseassignby'] }}</span>
                                            </div>
                                        @endif
                                        @if($activity['statuschangedby'] ?? null)
                                            <div>
                                                <span class="text-slate-600 dark:text-slate-400">Changed by:</span>
                                                <span class="ml-2 font-medium text-slate-900 dark:text-white">{{ $activity['statuschangedby'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="py-12 text-center">
                    <svg class="w-12 h-12 mx-auto text-slate-400 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-slate-600 dark:text-slate-400">No activities recorded for this case yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
