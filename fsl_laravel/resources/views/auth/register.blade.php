<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Fluree</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fdf4ff',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9'
                        },
                        secondary: {
                            500: '#06b6d4',
                            600: '#0891b2'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { min-height: 100vh; }
        .glass {
            background: rgba(15, 23, 42, 0.80);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .dark .glass {
            background: rgba(15, 23, 42, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 overflow-hidden">
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute -left-24 top-14 h-72 w-72 rounded-full bg-gradient-to-br from-primary-500/30 via-cyan-400/20 to-transparent blur-3xl animate-blob"></div>
        <div class="absolute right-0 bottom-16 h-80 w-80 rounded-full bg-gradient-to-br from-emerald-500/25 via-sky-400/10 to-transparent blur-3xl animate-blob" style="animation-delay: 2s;"></div>
        <div class="absolute left-1/2 top-2/3 h-56 w-56 -translate-x-1/2 rounded-full bg-gradient-to-br from-violet-500/15 via-fuchsia-500/10 to-transparent blur-3xl"></div>
    </div>

    <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <button id="themeToggle" class="absolute top-6 right-6 z-20 rounded-2xl bg-white/80 p-3 dark:bg-gray-900/80 glass backdrop-blur-sm shadow-xl transition duration-300 hover:scale-110">
            <svg id="sunIcon" class="w-5 h-5 text-emerald-600 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.706a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1z"/>
            </svg>
            <svg id="moonIcon" class="w-5 h-5 text-gray-800 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
        </button>

        <div class="glass p-10 rounded-3xl shadow-2xl backdrop-blur-xl border-0">
            <div class="text-center mb-10">
                <div class="mx-auto w-24 h-24 bg-gradient-to-br from-emerald-500 via-teal-500 to-emerald-600 rounded-3xl flex items-center justify-center mb-6 shadow-2xl hover:scale-110 transition-all duration-300">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent mb-4">
                    Create Account
                </h1>
                <p class="text-gray-600 dark:text-gray-300 text-lg">Join Fluree today</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-5 py-4 bg-white/60 dark:bg-gray-900/60 glass backdrop-blur-sm border border-gray-200 dark:border-gray-600 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all duration-300 @error('name') border-red-400 ring-2 ring-red-400/30 @enderror"
                           placeholder="John Doe">
                    @error('name') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-5 py-4 bg-white/60 dark:bg-gray-900/60 glass backdrop-blur-sm border border-gray-200 dark:border-gray-600 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all duration-300 @error('email') border-red-400 ring-2 ring-red-400/30 @enderror"
                           placeholder="john@example.com">
                    @error('email') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="relative">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Password</label>
                    <input type="password" name="password" id="regPassword" required
                           class="w-full px-5 py-4 bg-white/60 dark:bg-gray-900/60 glass backdrop-blur-sm border border-gray-200 dark:border-gray-600 rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-500/30 focus:border-teal-500 transition-all duration-300 pr-12 @error('password') border-red-400 ring-2 ring-red-400/30 @enderror"
                           placeholder="••••••••">
                    <button type="button" onclick="toggleRegPassword()" class="absolute right-4 top-14 text-gray-500 hover:text-emerald-600">
                        <svg id="regEyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                    @error('password') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="terms" required class="w-4 h-4 text-emerald-600 bg-white/60 border-gray-300 dark:border-gray-600 rounded focus:ring-emerald-500">
                        <span class="ml-2 text-xs text-gray-600 dark:text-gray-300">I agree to the Terms & Privacy Policy</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold py-5 px-6 rounded-3xl transition-all duration-300 shadow-2xl hover:shadow-3xl hover:-translate-y-1">
                    Create Account
                </button>
            </form>

        </div>
    </div>

    <script>
        // Same dark mode toggle as login
        const html = document.documentElement;
        const themeToggle = document.getElementById('themeToggle');
        const sunIcon = document.getElementById('sunIcon');
        const moonIcon = document.getElementById('moonIcon');
        function initTheme() {
            if (localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                html.classList.add('dark');
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
        }
        themeToggle?.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
            sunIcon.classList.toggle('hidden');
            moonIcon.classList.toggle('hidden');
        });
        initTheme();

        // Password toggle
        function toggleRegPassword() {
            const pwd = document.getElementById('regPassword');
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
