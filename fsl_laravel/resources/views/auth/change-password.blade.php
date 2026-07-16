@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-950 dark:text-white">Change Password</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-2">Update your account password securely</p>
        </div>
    </div>

    <!-- Change Password Form -->
    <div class="max-w-2xl">
        <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900 shadow-lg overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-700 dark:bg-slate-800">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-white">Update Your Password</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Choose a strong password with at least 8 characters</p>
            </div>

            <form action="{{ route('password.update') }}" method="POST" class="p-6 space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 border border-red-200 p-4 dark:bg-red-900/20 dark:border-red-800">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    @if ($errors->count() === 1)
                                        There is 1 error in the form
                                    @else
                                        There are {{ $errors->count() }} errors in the form
                                    @endif
                                </h3>
                                <ul class="mt-2 list-inside list-disc text-sm text-red-700 dark:text-red-300 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="rounded-lg bg-green-50 border border-green-200 p-4 dark:bg-green-900/20 dark:border-green-800">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="ml-3 text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Current Password
                    </label>
                    <input type="password" id="current_password" name="current_password" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-fluree-blue/50 focus:border-fluree-blue transition dark:bg-slate-800 dark:border-slate-600 dark:text-white dark:placeholder-slate-400 @error('current_password') border-red-500 @enderror"
                        placeholder="Enter your current password">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        New Password
                    </label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-fluree-blue/50 focus:border-fluree-blue transition dark:bg-slate-800 dark:border-slate-600 dark:text-white dark:placeholder-slate-400 @error('password') border-red-500 @enderror"
                        placeholder="Enter your new password (minimum 8 characters)">
                    <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        <p>Password requirements:</p>
                        <ul class="list-disc list-inside mt-1 space-y-0.5">
                            <li>At least 8 characters</li>
                            <li>Include uppercase and lowercase letters</li>
                            <li>Include numbers and special characters</li>
                        </ul>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Confirm Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-fluree-blue/50 focus:border-fluree-blue transition dark:bg-slate-800 dark:border-slate-600 dark:text-white dark:placeholder-slate-400 @error('password_confirmation') border-red-500 @enderror"
                        placeholder="Confirm your new password">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 bg-fluree-blue hover:bg-blue-700 text-white px-6 py-2.5 font-medium rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Update Password
                    </button>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-slate-200 hover:bg-slate-300 text-slate-900 px-6 py-2.5 font-medium rounded-lg transition dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Tips -->
    <div class="rounded-lg border border-amber-200 bg-amber-50 p-6 dark:border-amber-800 dark:bg-amber-900/20">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>
                <h3 class="font-semibold text-amber-900 dark:text-amber-100">Security Tips</h3>
                <ul class="mt-2 text-sm text-amber-800 dark:text-amber-200 space-y-1 list-disc list-inside">
                    <li>Never share your password with anyone</li>
                    <li>Use a unique password not used elsewhere</li>
                    <li>Change your password regularly</li>
                    <li>Log out after changing your password</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
