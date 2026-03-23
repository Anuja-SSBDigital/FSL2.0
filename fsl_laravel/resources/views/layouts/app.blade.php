


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fluree App') - Fluree</title>
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
<body class="bg-gray-900 text-gray-100 dark">
    <!-- Header -->
    <header class="bg-gray-800/95 backdrop-blur-md shadow-lg border-b border-gray-700 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center h-14">
                <div class="flex items-center">
                    <!-- Mobile toggle -->
                    <button id="mobile-toggle" class="lg:hidden p-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700 mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-xl font-bold bg-gradient-to-r from-fluree-blue to-blue-400 bg-clip-text text-transparent">Fluree Admin</h1>
                </div>
                <!-- Search -->
                <div class="flex-1 max-w-md mx-8">
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-3 top-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-fluree-blue/50 focus:border-fluree-blue transition">
                    </div>
                </div>
                <!-- Right side -->
                <div class="flex items-center space-x-3">
                    <!-- Notifications -->
                    <button class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-1 right-1 block w-2 h-2 bg-red-400 rounded-full"></span>
                    </button>
                    <!-- Dark toggle -->
                    <button id="dark-toggle" class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>
                    <!-- Profile dropdown -->
                    <div class="relative">
                        <button id="profile-btn" class="flex items-center space-x-2 p-2 hover:bg-gray-700 rounded-lg">
                            <div class="w-8 h-8 bg-gradient-to-r from-fluree-blue to-blue-500 rounded-full flex items-center justify-center font-semibold text-white">{{ substr(($user['username'] ?? 'User')[0] ?? 'U', 0, 1) }}</div>
                            <span class="text-sm font-medium hidden sm:block">{{ $user['username'] ?? $user['name'] ?? 'User' }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="profile-menu" class="absolute right-0 mt-2 w-48 bg-gray-700 rounded-xl shadow-2xl border border-gray-600 hidden py-1 z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-600 rounded-t-xl">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-600 rounded-b-xl">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1">
        <!-- Sidebar -->
        @auth
        <div id="sidebar" class="fixed inset-y-14 lg:inset-y-0 left-0 z-50 w-16 lg:w-64 bg-gray-900 shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto">
            <!-- Logo/User top -->
            <div class="p-4 border-b border-gray-800 lg:border-b-0">
                <div class="flex items-center lg:space-x-3 lg:pl-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-fluree-blue to-blue-500 rounded-xl flex items-center justify-center lg:hidden">
                        <span class="text-xs font-bold text-white">F</span>
                    </div>
                    <div class="hidden lg:block">
                        <h2 class="text-lg font-bold text-white">Fluree Admin</h2>
                        <p class="text-xs text-gray-400">Dashboard</p>
                    </div>
                </div>
            </div>
            <!-- Nav -->
            <nav class="mt-6 px-2 lg:px-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-fluree-blue text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-0 lg:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="hidden lg:inline font-medium">Dashboard</span>
                </a>
                <a href="#" class="group flex items-center px-3 py-3 rounded-xl transition-all text-gray-400 hover:text-white hover:bg-gray-800">
                    <svg class="w-5 h-5 mr-0 lg:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="hidden lg:inline font-medium">Profile</span>
                </a>
                <a href="#" class="group flex items-center px-3 py-3 rounded-xl transition-all text-gray-400 hover:text-white hover:bg-gray-800">
                    <svg class="w-5 h-5 mr-0 lg:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="hidden lg:inline font-medium">Fluree Data</span>
                </a>
                <div class="mt-4 pt-4 border-t border-gray-800">
                    <a href="#" class="group flex items-center px-3 py-3 rounded-xl transition-all text-gray-400 hover:text-white hover:bg-gray-800">
                        <svg class="w-5 h-5 mr-0 lg:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.326.015.56.999 2.573 1.066zM12 12a3 3 0 100-6 3 3 0 000 6z"></path>
                        </svg>
                        <span class="hidden lg:inline font-medium">Settings</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="group flex items-center w-full text-left px-3 py-3 rounded-xl transition-all text-gray-400 hover:text-red-400 hover:bg-gray-800" onclick="event.preventDefault(); this.closest('form').submit();">
                            <svg class="w-5 h-5 mr-0 lg:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="hidden lg:inline font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
            <nav class="mt-8 px-4">
                <a href="{{ route('dashboard') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-fluree-blue text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="#" class="group flex items-center px-4 py-3 mt-2 text-sm font-medium rounded-xl transition text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </a>
                <a href="#" class="group flex items-center px-4 py-3 mt-2 text-sm font-medium rounded-xl transition text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Fluree Data
                </a>
                <a href="#" class="group flex items-center px-4 py-3 mt-2 text-sm font-medium rounded-xl transition text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.326.015.56.999 2.573 1.066zM12 12a3 3 0 100-6 3 3 0 000 6z"></path>
                    </svg>
                    Settings
                </a>
                <a href="{{ route('logout') }}" class="group flex items-center px-4 py-3 mt-4 text-sm font-medium rounded-xl transition text-gray-700 hover:bg-red-50 hover:text-red-600" onclick="event.preventDefault(); this.closest('form').submit();">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </a>
            </nav>
        </div>
<div class="lg:ml-16 xl:ml-64 p-6 space-y-6">
            <!-- Mobile sidebar overlay -->
            <div id="mobile-overlay" class="fixed inset-0 z-40 bg-black/50 lg:hidden hidden" aria-hidden="true"></div>
            @yield('content')
        </div>
        @else
        <div class="p-6 lg:p-8 space-y-6">
            @yield('content')
        </div>
        @endauth
    </div>

    <!-- Scripts -->
    @auth
    <script>
        // Sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const mobileToggle = document.getElementById('mobile-toggle');
        const mobileOverlay = document.getElementById('mobile-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            mobileOverlay.classList.toggle('hidden');
        }

        mobileToggle?.addEventListener('click', toggleSidebar);
        mobileOverlay?.addEventListener('click', toggleSidebar);

        // Profile dropdown
        const profileBtn = document.getElementById('profile-btn');
        const profileMenu = document.getElementById('profile-menu');
        profileBtn?.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
        });

        // Close dropdown on outside click
        document.addEventListener('click', (e) => {
            if (!profileBtn?.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });

        // Dark mode toggle (placeholder)
        document.getElementById('dark-toggle')?.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
        });
    </script>
    @endauth
</body>
</html>

