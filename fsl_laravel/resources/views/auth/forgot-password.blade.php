<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Fluree</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            500: '#8b5cf6',
                            600: '#7c3aed'
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
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 overflow-hidden">
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute -left-24 top-14 h-72 w-72 rounded-full bg-gradient-to-br from-primary-500/30 via-cyan-400/20 to-transparent blur-3xl animate-blob"></div>
        <div class="absolute right-0 bottom-16 h-80 w-80 rounded-full bg-gradient-to-br from-emerald-500/25 via-sky-400/10 to-transparent blur-3xl animate-blob" style="animation-delay: 2s;"></div>
        <div class="absolute left-1/2 top-2/3 h-56 w-56 -translate-x-1/2 rounded-full bg-gradient-to-br from-violet-500/15 via-fuchsia-500/10 to-transparent blur-3xl"></div>
    </div>

    <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">
            <button id="themeToggle" class="absolute top-6 right-6 z-20 rounded-2xl bg-white/80 p-3 dark:bg-gray-900/80 glass backdrop-blur-sm shadow-xl transition duration-300 hover:scale-110">
            <svg id="sunIcon" class="w-5 h-5 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg id="moonIcon" class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
        </button>

        <div class="glass p-6 rounded-3xl shadow-2xl backdrop-blur-xl mx-auto">
            <div class="text-center mb-8">
                <div class="mx-auto w-20 h-20 bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center mb-5 shadow-xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-primary-700 bg-clip-text text-transparent mb-3">
                    Forgot Password
                </h1>
                <p class="text-gray-600 dark:text-gray-300">Enter your email and we'll send you a reset link</p>
            </div>

            @if (session('status'))
                <div class="p-4 bg-green-100/80 dark:bg-green-900/50 border border-green-200/50 rounded-2xl text-green-700 dark:text-green-300 backdrop-blur mb-6">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('forgot-password') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full rounded-3xl border border-slate-700/90 bg-slate-900 px-4 py-3 text-slate-100 placeholder:text-slate-500 outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-500/15"
                               placeholder="your@email.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-3 rounded-2xl transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-0.5">
                        Send Reset Link
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        // Dark mode toggle (same as others)
        const html = document.documentElement;
        document.getElementById('themeToggle')?.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
        });
    </script>
</body>
</html>
