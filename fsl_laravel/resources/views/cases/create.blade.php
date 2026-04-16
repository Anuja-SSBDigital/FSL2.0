@extends('layouts.app')

@section('title', 'Create Case')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Create New Case</h1>
            <p class="text-gray-400 mt-1">Fill details to generate case</p>
        </div>
        <a href="{{ route('dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all">
            ← Back to Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 px-6 py-4 rounded-2xl">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-6 py-4 rounded-2xl">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Case Number Preview -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Case Number Preview</h3>
        <div id="caseNumberPreview" class="text-2xl font-bold text-white bg-gray-900/50 border-2 border-dashed border-fluree-blue rounded-xl p-6 text-center font-mono">
            Select Department & Division to generate
        </div>
        <p class="text-sm text-gray-500 mt-2">Format: RFSL/EE/YYYY/DIV/0001 (auto-incremented)</p>
    </div>

    <form method="POST" action="{{ route('cases.store') }}" id="caseForm" class="bg-gray-800/50 border border-gray-700 rounded-2xl overflow-hidden">
        @csrf
        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Department <span class="text-red-400">*</span></label>
                    <select name="dept_id" id="dept_id" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-fluree-blue focus:border-fluree-blue transition" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept['_id'] }}" data-dept-code="{{ $dept['dept_code'] ?? '' }}">
                                {{ $dept['dept_name'] ?? 'N/A' }} ({{ $dept['dept_code'] ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Division -->
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Division <span class="text-red-400">*</span></label>
                    <select name="div_id" id="div_id" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-fluree-blue focus:border-fluree-blue transition" disabled required>
                        <option value="">Select Division (after Dept)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- No of Exhibits -->
                <div>
                    <label class="block text-sm font-medium text-white mb-2">No. of Exhibits <span class="text-red-400">*</span></label>
                    <input type="number" name="no_of_exhibits" id="no_of_exhibits" min="1" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-fluree-blue focus:border-fluree-blue transition" required>
                </div>

                <!-- Agency -->
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Agency <span class="text-red-400">*</span></label>
                    <input type="text" name="agency" id="agency" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-fluree-blue focus:border-fluree-blue transition" required>
                </div>

                <!-- Note -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-medium text-white mb-2">Note</label>
                    <textarea name="note" id="note" rows="3" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-fluree-blue focus:border-fluree-blue transition resize-vertical"></textarea>
                </div>
            </div>
        </div>

        <div class="px-8 py-6 bg-gray-900/50 border-t border-gray-700">
            <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-fluree-blue to-blue-600 hover:from-fluree-blue hover:to-blue-700 text-white font-semibold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all disabled:opacity-50" disabled id="submitBtn">
                Create Case
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deptSelect = document.getElementById('dept_id');
    const divSelect = document.getElementById('div_id');
    const preview = document.getElementById('caseNumberPreview');
    const form = document.getElementById('caseForm');
    const submitBtn = document.getElementById('submitBtn');
    const noExhibits = document.getElementById('no_of_exhibits');
    const agency = document.getElementById('agency');

    function updatePreview() {
        const deptId = deptSelect.value;
        const divId = divSelect.value;
        const year = new Date().getFullYear();

        if (deptId && divId) {
            const divOption = divSelect.selectedOptions[0];
            const divCode = divOption ? divOption.dataset.divCode || 'UNK' : 'UNK';
            preview.textContent = `RFSL/EE/${year}/${divCode.toUpperCase()}/0001`;
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50');
        } else {
            preview.textContent = 'Select Department & Division to generate';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50');
        }
    }

    // Load divisions on dept change
    deptSelect.addEventListener('change', function() {
        const deptId = this.value;
        divSelect.innerHTML = '<option value="">Loading...</option>';
        divSelect.disabled = true;

        if (deptId) {
            fetch(`/cases/divisions/${deptId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                divSelect.innerHTML = '<option value="">Select Division</option>';
                data.forEach(div => {
                    const option = document.createElement('option');
                    option.value = div._id;
                    option.textContent = `${div.div_name} (${div.div_code})`;
                    option.dataset.divCode = div.div_code;
                    divSelect.appendChild(option);
                });
                divSelect.disabled = false;
                updatePreview();
            })
            .catch(error => {
                console.error('Error:', error);
                divSelect.innerHTML = '<option value="">Error loading divisions</option>';
                divSelect.disabled = false;
            });
        } else {
            divSelect.innerHTML = '<option value="">Select Division (after Dept)</option>';
            divSelect.disabled = true;
            updatePreview();
        }
    });

    divSelect.addEventListener('change', updatePreview);

    // Enable submit when all required filled
    ['no_of_exhibits', 'agency'].forEach(id => {
        document.getElementById(id).addEventListener('input', updatePreview);
    });

    // Form submit validation
    form.addEventListener('submit', function(e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            alert('Please fill all required fields');
        }
    });
});
</script>

<style>
#caseNumberPreview {
    font-family: 'Courier New', monospace;
}
</style>
@endsection

