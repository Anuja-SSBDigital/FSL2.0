<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fluree</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9'
                        },
                        emerald: {
                            500: '#10b981',
                            600: '#059669'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { min-height: 100vh; }
        .glass {
            background: rgba(15, 23, 42, 0.80);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(12px, -16px) scale(1.05); }
            66% { transform: translate(-10px, 14px) scale(0.95); }
        }
        .animate-blob { animation: blob 8s infinite ease-in-out; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute -left-20 top-16 h-60 w-60 rounded-full bg-gradient-to-br from-primary-500/30 via-cyan-400/20 to-transparent blur-3xl animate-blob"></div>
        <div class="absolute right-0 bottom-24 h-72 w-72 rounded-full bg-gradient-to-br from-emerald-500/25 via-sky-400/10 to-transparent blur-3xl animate-blob" style="animation-delay: 2s;"></div>
        <div class="absolute left-1/2 top-2/3 h-44 w-44 -translate-x-1/2 rounded-full bg-gradient-to-br from-violet-500/15 via-fuchsia-500/10 to-transparent blur-3xl"></div>
    </div>

    <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-[360px]">
            <div class="glass relative overflow-hidden rounded-[28px] border border-white/10 p-5 shadow-[0_24px_64px_rgba(15,23,42,0.45)]">
                <div class="mb-5 text-center">
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-3xl bg-gradient-to-br from-primary-600 to-emerald-500 text-white shadow-lg shadow-primary-500/20">
                        <span class="text-lg font-semibold">F</span>
                    </div>
                    <h1 class="text-2xl font-semibold text-white">Welcome Back</h1>
                    <p class="mt-2 text-sm text-slate-400">Sign in to your Fluree account.</p>
                </div>

                @if (session('success'))
                    <div class="mb-4 rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-3 text-sm text-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-3xl border border-rose-500/20 bg-rose-500/10 p-3 text-sm text-rose-200">
                        <ul class="list-inside list-disc space-y-1 pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="username" class="text-sm font-medium text-slate-300">Email or Username</label>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                            class="mt-2 w-full rounded-3xl border border-slate-700/90 bg-slate-900 px-4 py-3 text-slate-100 placeholder:text-slate-500 outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-500/15"
                            placeholder="you@example.com or username">
                    </div>

                    <div>
                        <label for="password" class="text-sm font-medium text-slate-300">Password</label>
                        <input id="password" name="password" type="password" required
                            class="mt-2 w-full rounded-3xl border border-slate-700/90 bg-slate-900 px-4 py-3 text-slate-100 placeholder:text-slate-500 outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-500/15"
                            placeholder="••••••••">
                    </div>

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-400">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-primary-500 focus:ring-primary-500">
                            Remember me
                        </label>
                        <a href="{{ route('showForgotPasswordForm') }}" class="text-sm font-medium text-slate-200 transition hover:text-primary-300">Forgot password?</a>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="inline-flex w-full items-center justify-center rounded-3xl bg-gradient-to-r from-primary-600 via-emerald-500 to-cyan-500 px-5 py-3 text-sm font-semibold text-white shadow-2xl shadow-primary-500/20 transition hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-primary-500/20">
                        Sign in
                    </button>
                </form>

                <div class="mt-5 border-t border-white/10 pt-4 text-center text-sm text-slate-400">
                    <p class="uppercase tracking-[0.22em] text-slate-500">Or continue with</p>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        <button class="inline-flex items-center justify-center gap-2 rounded-3xl border border-slate-700/80 bg-slate-900 px-3 py-2 text-sm text-slate-200 transition hover:border-primary-500 hover:text-white">
                            <span class="h-5 w-5 rounded-full bg-slate-700 flex items-center justify-center text-xs">G</span>
                            Google
                        </button>
                        <button class="inline-flex items-center justify-center gap-2 rounded-3xl border border-slate-700/80 bg-slate-900 px-3 py-2 text-sm text-slate-200 transition hover:border-cyan-500 hover:text-white">
                            <span class="h-5 w-5 rounded-full bg-slate-700 flex items-center justify-center text-xs">M</span>
                            Microsoft
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
