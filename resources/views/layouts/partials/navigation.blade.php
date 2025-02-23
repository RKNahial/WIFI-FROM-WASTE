<nav class="gradient-bg shadow-lg border-b border-[#2A632A] dark:border-[#1A3F1A] fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <img src="{{ asset('assets/img/logo/logo-white.png') }}" 
                    alt="WIFI FROM WASTE" 
                    class="h-8"> 
            </div>
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle -->
                <button onclick="toggleDarkMode()" 
                        class="p-2 text-white hover:text-white hover:bg-[#3C8F3A] rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3C8F3A] transition-all duration-150">
                    <i class="fas fa-moon dark:hidden"></i>
                    <i class="fas fa-sun hidden dark:block"></i>
                </button>
                <!-- Trash Bin Status -->
                <div class="relative">
                    <button onclick="toggleModal('trashBinModal')" 
                            class="relative p-2 text-white hover:text-white hover:bg-[#3C8F3A] rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3C8F3A] transition-all duration-150">
                        <i class="fas fa-box text-xl"></i>
                    </button>
                </div>
                <!-- Download Report Button -->
                <button onclick="alert('Report generation is not available in static mode')" 
                   class="inline-flex items-center px-4 py-2 bg-white text-emerald-800 text-sm font-medium rounded-lg hover:bg-emerald-50 transition-all duration-150 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i>
                    Generate Report
                </button>
                <!-- User Menu -->
                @include('layouts.partials.user-menu')
            </div>
        </div>
    </div>
</nav>