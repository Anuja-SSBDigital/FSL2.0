<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Fluree</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto p-8">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">
                Hello, {{ $user['username'] ?? $user['name'] ?? 'User' }} 👋
            </h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl">
                    Logout
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow p-8">
            <h2 class="text-2xl font-semibold mb-6">Your Fluree Data</h2>
            <pre class="bg-gray-900 text-green-400 p-6 rounded-xl overflow-auto text-sm">
{{ json_encode($exampleData, JSON_PRETTY_PRINT) }}
            </pre>
        </div>
    </div>
</body>
</html>