<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fluree App') - Fluree</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class', // ← This is important!
            content: [],
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

        body {
            font-family: 'Inter', sans-serif;
        }

        /* DataTables Tailwind Dark Fix */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            background-color: #1f2937 !important;
            color: white !important;
            border: 1px solid #374151 !important;
            border-radius: 0.5rem;
            padding: 6px;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_filter label,
        .dataTables_wrapper .dataTables_length label {
            color: #9ca3af !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #d1d5db !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6 !important;
            color: white !important;
            border-radius: 6px;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
</head>

<body class="bg-gray-100 dark:bg-gradient-to-br dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 text-gray-900 dark:text-gray-100 min-h-screen">
    <header class="h-14 fixed top-0 left-0 right-0 z-50 bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <div class="flex items-center">
                    <!-- Header -->

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
                    <button class="p-2 text-gray-500 dark:text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-1 right-1 block w-2 h-2 bg-red-400 rounded-full"></span>
                    </button>
                    <!-- Dark toggle -->
                    <!-- Dark/Light Toggle -->
                    <!-- Light/Dark Toggle -->
                    <button id="theme-toggle" class="p-2 text-gray-500 dark:text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-all">
                        <svg id="sun-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 16v2m9-9h-2M4 12H2m15.364 6.364l-1.591-1.591M6.343 6.343l-1.591 1.591m12.728 0l-1.591-1.591M6.343 17.657l-1.591-1.591M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg id="moon-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                    <!-- Profile dropdown -->
                    <div class="relative">
                        <button id="profile-btn" class="flex items-center space-x-2 p-2 hover:bg-gray-700 rounded-lg">
                            <div class="w-8 h-8 bg-gradient-to-r from-fluree-blue to-blue-500 rounded-full flex items-center justify-center font-semibold text-white">{{ substr(($user['username'] ?? 'User')[0] ?? 'U', 0, 1) }}</div>
                            <span class="text-sm font-medium hidden sm:block">{{ $user['username'] ?? $user['name'] ?? 'User' }}</span>
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <div class="flex flex-1 min-h-screen">
        <!-- Sidebar - FIXED VISIBLE ON ALL SIZES -->
        @if(Session::has('fluree_user') || isset($user))
        <div id="sidebar" class="fixed top-14 left-0 h-[calc(100vh-56px)] w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 overflow-y-auto z-40">
            <div class="p-4 border-b border-gray-200 dark:border-gray-800 lg:border-b-0">
                <div class="flex items-center lg:space-x-3 lg:pl-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-fluree-blue to-blue-500 rounded-xl flex items-center justify-center lg:hidden">
                        <span class="text-xs font-bold text-white">F</span>
                    </div>
                    <div class="hidden lg:block">
                        <h2 class="text-lg font-bold text-white">Fluree Admin</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Dashboard</p>
                    </div>
                </div>
            </div>
            <!-- Enhanced Nav - Master-detail menu -->
            <nav class="mt-6 px-2 lg:px-4 space-y-1">
                <!-- Dashboard Master -->
                <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-fluree-blue text-white shadow-lg' : 'text-gray-500 dark:text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-0 lg:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="hidden lg:inline font-medium">Dashboard</span>
                </a>

                <!-- User Management Master (with role division) -->
                <div>
                    <a href="#" class="group flex items-center px-3 py-3 rounded-xl transition-all text-gray-500 dark:text-gray-400 hover:text-white hover:bg-gray-800 font-semibold cursor-pointer"> <svg class="w-5 h-5 mr-0 lg:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="hidden lg:inline font-medium">User Management</span>
                        <svg class="w-4 h-4 ml-auto text-gray-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <div class="ml-6 lg:ml-10 space-y-1 hidden group-hover:block lg:block">
                        <!-- Users Submenu -->
                        <a href="{{ route('userpage') }}" class="group flex items-center px-3 py-2 rounded-lg transition-all text-xs text-gray-500 hover:text-white hover:bg-white dark:bg-gray-800/50 pl-4">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Users
                        </a>
                        <!-- Roles Division -->
                        <a href="" class="group flex items-center px-3 py-2 rounded-lg transition-all text-xs text-gray-500 hover:text-white hover:bg-white dark:bg-gray-800/50 pl-4">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Roles & Permissions
                        </a>
                    </div>
                </div>

                <!-- Fluree Data -->
                <a href="#" class="group flex items-center px-3 py-3 rounded-xl transition-all text-gray-500 dark:text-gray-500 dark:text-gray-400 hover:text-white hover:bg-gray-800">
                    <svg class="w-5 h-5 mr-0 lg:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="hidden lg:inline font-medium">Fluree Data</span>
                </a>

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800">
                    <a href="#" class="group flex items-center px-3 py-3 rounded-xl transition-all text-gray-500 dark:text-gray-400 hover:text-white hover:bg-gray-800">
                        <svg class="w-5 h-5 mr-0 lg:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.326.015.56.999 2.573 1.066zM12 12a3 3 0 100-6 3 3 0 000 6z"></path>
                        </svg>
                        <span class="hidden lg:inline font-medium">Settings</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="group flex items-center w-full text-left px-3 py-3 rounded-xl transition-all text-gray-500 dark:text-gray-400 hover:text-red-400 hover:bg-gray-800" onclick="event.preventDefault(); this.closest('form').submit();">
                            <svg class="w-5 h-5 mr-0 lg:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="hidden lg:inline font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>

        </div>

        <div class="{{ (Session::has('fluree_user') || isset($user)) ? 'ml-64' : '' }} pt-14 flex-1 overflow-y-auto">
            <div class="max-w-7xl mx-auto px-6 py-6 space-y-8">
                @yield('content')
            </div>
        </div>
        @else
        <div class="{{ (Session::has('fluree_user') || isset($user)) ? 'ml-64' : '' }} pt-14 flex-1 overflow-y-auto">
            <div class="max-w-7xl mx-auto px-6 py-6 space-y-8">
                @yield('content')
            </div>
        </div>
        @endif
    </div>

    <!-- Scripts -->
    @if(Session::has('fluree_user') || isset($user))
    <!-- <script>
        // Sidebar submenu toggle
        document.querySelectorAll('.group:has(a)').forEach(parent => {
            const submenu = parent.querySelector('.ml-6, .ml-10');
            const toggle = parent.querySelector('a:first-child');
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                submenu.classList.toggle('hidden');
            });
        });

        const userManagement = document.querySelector('a[href="#"][class*="User Management"]');
        if (userManagement) {
            const submenu = userManagement.parentElement.querySelector('.ml-6, .ml-10');

            userManagement.addEventListener('click', function(e) {
                e.preventDefault();
                submenu.classList.toggle('hidden');

                // Optional: rotate the arrow
                const arrow = this.querySelector('svg:last-child');
                if (arrow) arrow.classList.toggle('rotate-180');
            });
        }

        // Profile dropdown
        const profileBtn = document.getElementById('profile-btn');
        const profileMenu = document.getElementById('profile-menu');
        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', (e) => {
                e.stopImmediatePropagation();
                profileMenu.classList.toggle('hidden');
            });
        }

        // Close profile dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (profileBtn && !profileBtn.contains(e.target)) {
                profileMenu?.classList.add('hidden');
            }
        });
    </script> -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        // User Management Submenu Toggle
        const userBtn = document.getElementById('user-management-btn');
        const submenu = document.getElementById('user-submenu');
        const arrow = document.getElementById('user-arrow');

        if (userBtn && submenu) {
            userBtn.addEventListener('click', function(e) {
                e.preventDefault();
                submenu.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');
            });
        }

        // Profile dropdown (keep your existing one)
        const profileBtn = document.getElementById('profile-btn');
        const profileMenu = document.getElementById('profile-menu');
        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', (e) => {
                e.stopImmediatePropagation();
                profileMenu.classList.toggle('hidden');
            });
        }

        document.addEventListener('click', (e) => {
            if (profileBtn && !profileBtn.contains(e.target)) {
                profileMenu?.classList.add('hidden');
            }
        });

        const themeToggle = document.getElementById('theme-toggle');
        const sunIcon = document.getElementById('sun-icon');
        const moonIcon = document.getElementById('moon-icon');

        // Load saved preference or respect system preference
        function initTheme() {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            }
        }

        themeToggle.addEventListener('click', () => {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
        });

        // Initialize on load
        initTheme();
    </script>
    <script>
$(document).ready(function () {
    $('#usersTable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        responsive: true,
        dom: '<"flex flex-col md:flex-row md:justify-between md:items-center mb-4"lf>rt<"flex flex-col md:flex-row md:justify-between md:items-center mt-4"ip>',
    });
});
</script>
    @endif
</body>

</html>