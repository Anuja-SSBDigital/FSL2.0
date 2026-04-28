@extends('layouts.app')

@section('title', 'Case Details - ' . $caseNo)

@section('content')
<div class="mx-auto max-w-5xl py-8">
    <div class="rounded-[32px] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/40 dark:border-slate-700 dark:bg-slate-950 dark:shadow-black/20">
        <!-- Header -->
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-950 dark:text-white">Case Details</h1>
                <p class="mt-1 text-slate-600 dark:text-slate-400">Case Number: <span class="font-semibold">{{ $caseNo }}</span></p>
            </div>
            <div class="text-right">
                @php
                    $userRole = $user['role_id']['role'] ?? null;
                @endphp
                @if($userRole === 'SuperAdmin')
                    <span class="inline-block rounded-full bg-blue-100 px-4 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                        Admin View - All Data Visible
                    </span>
                @else
                    <span class="inline-block rounded-full bg-slate-100 px-4 py-1 text-sm font-medium text-slate-800 dark:bg-slate-800 dark:text-slate-200">
                        Department View
                    </span>
                @endif
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mt-6 flex flex-wrap gap-3">
            @php
                $statusColor = [
                    'Pending for Assign' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
                    'Assigned' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                    'In Progress' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200',
                    'Completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200',
                    'Pending' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200',
                ];
                $status = $caseDetails['status'] ?? 'Unknown';
                $colorClass = $statusColor[$status] ?? 'bg-slate-100 text-slate-800';
            @endphp
            <span class="inline-block rounded-full px-3 py-1 text-sm font-medium {{ $colorClass }}">
                Status: {{ $status }}
            </span>
        </div>

        <!-- Case Information Section -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-slate-950 dark:text-white">Case Information</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <!-- Case Number -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Case Number</p>
                    <p class="mt-2 text-lg font-semibold text-slate-950 dark:text-white">{{ $caseNo }}</p>
                </div>

                <!-- Evidence ID -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Evidence ID</p>
                    <p class="mt-2 text-lg font-semibold text-slate-950 dark:text-white">{{ $caseDetails['evidenceid'] ?? 'N/A' }}</p>
                </div>

                <!-- Department Code -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Department Code</p>
                    <p class="mt-2 text-lg font-semibold text-slate-950 dark:text-white">{{ $caseDetails['department_code'] ?? 'N/A' }}</p>
                </div>

                <!-- Division Code -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Division Code</p>
                    <p class="mt-2 text-lg font-semibold text-slate-950 dark:text-white">{{ $caseDetails['div_code'] ?? 'N/A' }}</p>
                </div>

                <!-- Institution Code -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Institution Code</p>
                    <p class="mt-2 text-lg font-semibold text-slate-950 dark:text-white">{{ $caseDetails['inst_code'] ?? 'N/A' }}</p>
                </div>

                <!-- Number of Exhibits -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Number of Exhibits</p>
                    <p class="mt-2 text-lg font-semibold text-slate-950 dark:text-white">{{ $caseDetails['noof_exhibits'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Agency Information Section -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-slate-950 dark:text-white">Agency Information</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <!-- Agency Name -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Agency Name</p>
                    <p class="mt-2 text-lg font-semibold text-slate-950 dark:text-white">{{ $caseDetails['agencyname'] ?? 'N/A' }}</p>
                </div>

                <!-- Agency Reference Number -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Agency Reference Number</p>
                    <p class="mt-2 text-lg font-semibold text-slate-950 dark:text-white">{{ $caseDetails['agencyreferanceno'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Assignment Information Section -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-slate-950 dark:text-white">Assignment Information</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <!-- Case Assigned User ID -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Assigned to User ID</p>
                    <p class="mt-2 text-lg font-semibold text-slate-950 dark:text-white">
                        {{ $caseDetails['caseassign_userid'] ? $caseDetails['caseassign_userid'] : 'Not Assigned' }}
                    </p>
                </div>

                <!-- Entered By -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Entered By</p>
                    <p class="mt-2 text-lg font-semibold text-slate-950 dark:text-white">{{ $caseDetails['enteredby'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- File & Hash Information Section -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-slate-950 dark:text-white">File & Hash Information</h2>
            <div class="mt-6 space-y-4">
                <!-- Receipt File -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Receipt File</p>
                    @if($caseDetails['receiptfilepath'])
                        <p class="mt-2">
                            <a href="{{ $caseDetails['receiptfilepath'] }}" target="_blank" rel="noopener noreferrer" class="text-fluree-blue hover:underline dark:text-blue-400">
                                Download Receipt PDF
                            </a>
                        </p>
                    @else
                        <p class="mt-2 text-slate-500 dark:text-slate-400">No receipt file available</p>
                    @endif
                </div>

                <!-- File Hash -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">File Hash (SHA256)</p>
                    @if($caseDetails['hash'])
                        <p class="mt-2 break-all text-sm font-mono text-slate-950 dark:text-white">{{ $caseDetails['hash'] }}</p>
                    @else
                        <p class="mt-2 text-slate-500 dark:text-slate-400">No hash available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        @if($caseDetails['notes'])
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-slate-950 dark:text-white">Notes</h2>
            <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                <p class="text-slate-950 dark:text-slate-100">{{ $caseDetails['notes'] }}</p>
            </div>
        </div>
        @endif

        <!-- Timestamps Section -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-slate-950 dark:text-white">Timestamps</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <!-- Created Date -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Created Date</p>
                    <p class="mt-2 text-slate-950 dark:text-white">
                        {{ $caseDetails['createddate'] ? \Carbon\Carbon::parse($caseDetails['createddate'])->format('Y-m-d H:i:s') : 'N/A' }}
                    </p>
                </div>

                <!-- Updated Date -->
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Last Updated</p>
                    <p class="mt-2 text-slate-950 dark:text-white">
                        {{ $caseDetails['updateddate'] ? \Carbon\Carbon::parse($caseDetails['updateddate'])->format('Y-m-d H:i:s') : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-wrap gap-4">
            @php
                $userRole = $user['role_id']['role'] ?? null;
            @endphp
            
            @if($userRole === 'SuperAdmin')
                <a href="{{ route('cases.add-details', $caseNo) }}" class="rounded-3xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-900 dark:hover:bg-slate-800">
                    Edit Case Details
                </a>
                <a href="{{ route('cases.assign', $caseNo) }}" class="rounded-3xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-900 dark:hover:bg-slate-800">
                    Assign Case
                </a>
            @else
                <a href="{{ route('cases.add-details', $caseNo) }}" class="rounded-3xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-900 dark:hover:bg-slate-800">
                    View Full Details
                </a>
            @endif
            
            <a href="{{ route('dashboard') }}" class="rounded-3xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:hover:bg-slate-800">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
