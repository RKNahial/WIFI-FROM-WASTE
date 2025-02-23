<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WiFi from Waste') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('WifiFromWaste') }}">
                                <img src="{{ asset('assets/img/logo/logo-green.png') }}" alt="Logo" class="h-8 w-auto">
                            </a>
                        </div>
                    </div>

                    <!-- Right Side Navigation -->
                    <div class="flex items-center">
                        @auth
                            <div class="relative" x-data="{ open: false }">
                                <!-- Profile Dropdown Button -->
                                <button @click="open = !open" class="flex items-center space-x-3 hover:opacity-80 focus:outline-none">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-slate-900">{{ Auth::user()->name }}</span>
                                        <i class="fas fa-chevron-down ml-2 text-xs text-slate-500"></i>
                                    </div>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="open" 
                                     @click.away="open = false"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 z-50 ring-1 ring-black ring-opacity-5">
                                    <div class="px-4 py-2 border-b border-slate-200">
                                        <p class="text-sm font-medium text-slate-900">Admin Panel</p>
                                        <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                                    </div>
                                    <div class="py-1">
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-user-cog mr-2"></i> Profile Settings
                                        </a>
                                        <form action="{{ route('logout') }}" method="POST" class="block">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 focus:outline-none">
                                                <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
