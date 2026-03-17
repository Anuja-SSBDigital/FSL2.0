<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fluree App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Welcome Back</h1>
            <p class="text-gray-600 mt-2">Login with your Fluree credentials</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text" 
                       name="username" 
                       value="{{ old('username') }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" 
                       name="password" 
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-xl transition">
                Login
            </button>
        </form>

        @if ($errors->any())
            <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-xl text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif
    </div>
</body>
</html>