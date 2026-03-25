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
                        'fluree-blue': '#1e40af',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-2xl">
        <div class="text-center mb-10">
            <div class="mx-auto w-20 h-20 bg-gradient-to-r from-fluree-blue to-blue-600 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-3">Welcome Back</h1>
            <p class="text-gray-600">Sign in to your Fluree account</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Username</label>
                    <input type="text" 
                           name="username" 
                           value="{{ old('username') }}"
                           required
                           class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-fluree-blue/20 focus:border-fluree-blue transition duration-200 bg-gray-50 @error('username') border-red-300 bg-red-50 @enderror">
                    @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Password</label>
                    <input type="password" 
                           name="password" 
                           required
                           class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-fluree-blue/20 focus:border-fluree-blue transition duration-200 bg-gray-50 @error('password') border-red-300 bg-red-50 @enderror">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if ($errors->any() && !$errors->has('username') && !$errors->has('password'))
                    <div class="p-4 bg-red-100 border border-red-200 rounded-2xl text-red-700 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="p-4 bg-green-100 border border-green-200 rounded-2xl text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <button type="submit"
                        class="w-full bg-gradient-to-r from-fluree-blue to-blue-600 hover:from-fluree-blue/90 hover:to-blue-600/90 text-white font-semibold py-4 px-6 rounded-2xl transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5">
                    Sign In
                </button>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">Powered by Fluree & Laravel</p>
        </div>
    </div>
</body>
</html>
