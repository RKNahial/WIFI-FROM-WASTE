<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/img/system-icon.png') }}" type="image/png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-green': '#3C8F3A',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white">
    <!-- Navbar -->
    <nav class="fixed w-full bg-white shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center hover:opacity-80 transition-opacity">
                        <img src="{{ asset('assets/img/logo/logo-green.png') }}" 
                             alt="WifiFromWaste Logo" 
                             class="h-16 md:h-14 lg:h-18 w-auto">
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('WifiFromWaste') }}" 
                               class="px-4 py-2 rounded-lg text-brand-green hover:opacity-80 transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="px-4 py-2 rounded-lg text-brand-green hover:opacity-80 transition-colors">
                                Sign In
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="px-4 py-2 bg-brand-green text-white rounded-lg hover:opacity-80 transition-colors">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative h-screen flex items-center justify-center bg-cover bg-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('assets/img/hero-bg.jpg') }}');">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">
                WiFi from Waste Management System
            </h1>
            <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto">
                Monitor and manage recycling activities, track WiFi credits, and analyze environmental impact all in one place.
            </p>
            @if (Route::has('login'))
                @auth
                    <a href="{{ route('WifiFromWaste') }}" class="inline-block px-8 py-4 bg-brand-green text-white text-lg font-semibold rounded-lg hover:opacity-90 transition-all transform hover:scale-105">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-block px-8 py-4 bg-brand-green text-white text-lg font-semibold rounded-lg hover:opacity-90 transition-all transform hover:scale-105">
                        Get started
                    </a>
                @endauth
            @endif
        </div>
    </section>

    <!-- About Us Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                        Administrative Control Center
                    </h2>
                    <p class="text-lg text-gray-600">
                        Our management system allows administrators to oversee all aspects of the WiFi from Waste program, 
                        from tracking recycling metrics to monitoring connected devices.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
                       
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ asset('assets/img/admin-dashboard.jpg') }}" 
                         alt="Admin Dashboard" 
                         class="rounded-lg shadow-xl">
                    <div class="absolute -bottom-4 -right-4 bg-brand-green text-white p-3 rounded-lg max-w-[200px]">
                        <div class="text-xl font-bold">19,750kg</div>
                        <div class="text-xs">Total Recyclables Collected by Malaybalay CENRO in 2023</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Administrative Features</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="relative p-6 bg-white rounded-lg shadow-lg hover:transform hover:scale-105 transition-all">
                    <i class="fas fa-chart-bar text-4xl text-brand-green mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Analytics Dashboard</h3>
                    <p class="text-gray-600">Monitor recycling activity</p>
                </div>

                <!-- Feature 2 -->
                <div class="relative p-6 bg-white rounded-lg shadow-lg hover:transform hover:scale-105 transition-all">
                    <i class="fas fa-microchip text-4xl text-brand-green mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Device Monitoring</h3>
                    <p class="text-gray-600">Monitor connected devices</p>
                </div>

                <!-- Feature 3 -->
                <div class="relative p-6 bg-white rounded-lg shadow-lg hover:transform hover:scale-105 transition-all">
                    <i class="fas fa-wifi text-4xl text-brand-green mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">WiFi Monitoring</h3>
                    <p class="text-gray-600">Track network usage</p>
                </div>

                <!-- Feature 4 -->
                <div class="relative p-6 bg-white rounded-lg shadow-lg hover:transform hover:scale-105 transition-all">
                    <i class="fas fa-file-alt text-4xl text-brand-green mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Report Generation</h3>
                    <p class="text-gray-600">Create activity reports</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-brand-green py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <p>&copy; {{ date('Y') }} WifiFromWaste. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>