@extends('layouts.app')

@section('title', 'Edit Evidence Acceptance')

@section('content')
<div class="mx-auto max-w-5xl py-8">
    <div class="rounded-[32px] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/40 dark:border-slate-700 dark:bg-slate-950 dark:shadow-black/20">
        <h1 class="text-2xl font-semibold text-slate-950 dark:text-white">Update Evidence Acceptance</h1>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Modify evidence acceptance details.</p>

        @if(session('success'))
            <div class="mt-6 rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-950 dark:text-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-6 rounded-3xl border border-red-500/20 bg-red-500/10 p-4 text-red-950 dark:text-red-100">
                <ul class="space-y-2 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $caseNumber = $evidence['caseno'] ?? '';
            $caseNumberParts = explode('/', $caseNumber);
        @endphp

        <form method="POST" action="{{ route('cases.acceptance-update', $evidence['evidenceid'] ?? '') }}" enctype="multipart/form-data" id="editAcceptanceForm" class="mt-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Case Number Display (Read-only) -->
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Case No (Prefix)</label>
                    <input type="text" value="{{ $caseNumberParts[0] ?? 'N/A' }}/{{ $caseNumberParts[1] ?? 'N/A' }}" readonly class="mt-3 w-full rounded-3xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                </div>
                @if(count($caseNumberParts) > 2)
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Year</label>
                        <input type="text" value="{{ $caseNumberParts[2] ?? '' }}" readonly class="mt-3 w-full rounded-3xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Division/Code</label>
                        <input type="text" value="{{ $caseNumberParts[3] ?? '' }}" readonly class="mt-3 w-full rounded-3xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Number</label>
                        <input type="text" value="{{ $caseNumberParts[4] ?? '' }}" readonly class="mt-3 w-full rounded-3xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                    </div>
                @endif
            </div>

            <!-- Full Case Number -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Full Case Number</label>
                <input type="text" value="{{ $caseNumber }}" readonly class="mt-3 w-full rounded-3xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
            </div>

            <!-- File Upload -->
            <div>
                <label for="receipt_file" class="block text-sm font-medium text-slate-700 dark:text-slate-200">PDF Upload (Evidence Receipt)</label>
                @if($evidence['receiptfilepath'] ?? null)
                    <div class="mb-3 flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z"></path>
                        </svg>
                        <span>Current file: <a href="{{ $evidence['receiptfilepath'] }}" target="_blank" class="text-fluree-blue underline">View</a></span>
                    </div>
                @endif
                <input type="file" name="receipt_file" id="receipt_file" accept=".pdf" class="mt-3 w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-fluree-blue focus:ring-2 focus:ring-fluree-blue/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                <p class="mt-1 text-xs text-slate-500">Upload a new PDF to replace the current one (Max 25MB)</p>
                @error('receipt_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Exhibits Count -->
            <div>
                <label for="no_of_exhibits" class="block text-sm font-medium text-slate-700 dark:text-slate-200">No of Exhibits</label>
                <input type="number" id="no_of_exhibits" name="no_of_exhibits" min="1" value="{{ old('no_of_exhibits', $evidence['noof_exhibits'] ?? 1) }}" class="mt-3 w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-fluree-blue focus:ring-2 focus:ring-fluree-blue/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" required onchange="validateNoOfExhibits()" />
                @error('no_of_exhibits')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Reference and Agency -->
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="reference_no" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Reference No</label>
                    <input type="text" id="reference_no" name="reference_no" value="{{ old('reference_no', $evidence['agencyreferanceno'] ?? '') }}" maxlength="255" class="mt-3 w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-fluree-blue focus:ring-2 focus:ring-fluree-blue/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                    @error('reference_no')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="agency" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Police Station/Agency Name</label>
                    <input type="text" id="agency" name="agency" value="{{ old('agency', $evidence['agencyname'] ?? '') }}" maxlength="255" class="mt-3 w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-fluree-blue focus:ring-2 focus:ring-fluree-blue/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" required />
                    @error('agency')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="note" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Notes</label>
                <textarea id="note" name="note" rows="4" class="mt-3 w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-fluree-blue focus:ring-2 focus:ring-fluree-blue/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">{{ old('note', $evidence['notes'] ?? '') }}</textarea>
                @error('note')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Metadata Display -->
            @if($evidence)
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900/50">
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        <strong>Status:</strong> <span class="inline-block rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ $evidence['status'] ?? 'N/A' }}</span>
                    </p>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        <strong>Created By:</strong> {{ $evidence['enteredby'] ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        <strong>Created Date:</strong> {{ $evidence['createddate'] ?? 'N/A' }}
                    </p>
                </div>
            @endif

            <!-- Buttons -->
            <div class="flex gap-4 pt-6">
                <button type="submit" class="flex-1 rounded-3xl bg-fluree-blue px-6 py-3 font-medium text-white outline-none transition hover:bg-blue-700 focus:ring-2 focus:ring-fluree-blue/20 dark:bg-fluree-blue dark:hover:bg-blue-700">
                    Update
                </button>
                <a href="{{ route('dashboard') }}" class="flex-1 rounded-3xl border border-slate-300 bg-white px-6 py-3 text-center font-medium text-slate-900 outline-none transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                    Back
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function validateNoOfExhibits() {
        const input = document.getElementById('no_of_exhibits');
        if (input.value < 1) {
            alert('The number should be 1 or greater than 1.');
            input.value = '';
            return false;
        }
        return true;
    }

    document.getElementById('editAcceptanceForm').addEventListener('submit', function(e) {
        const agency = document.getElementById('agency').value;
        const noOfExhibits = document.getElementById('no_of_exhibits').value;

        if (!agency || !noOfExhibits) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }

        return true;
    });
</script>
@endsection
