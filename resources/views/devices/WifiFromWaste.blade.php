<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('assets/img/logo/system-logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            '50': '#f8fafc',
                            '900': '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: #3C8F3A;
        }
        .dark .gradient-bg {
            background: #3C8F3A;
        }
        .card-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f7fafc 100%);
        }
        .dark .card-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
        .metric-card {
            transition: all 0.3s ease;
        }
        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .table-row-hover:hover {
            background: linear-gradient(90deg, #f7fafc 0%, #edf2f7 100%);
        }
        .dark .table-row-hover:hover {
            background: linear-gradient(90deg, #1e293b 0%, #0f172a 100%);
        }
        .progress-bar {
            width: 100%;
            height: 6px;
            background-color: #e2e8f0;
            border-radius: 9999px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 9999px;
            transition: width 0.3s ease;
        }
        .dark .progress-bar {
            background-color: #334155;
        }
        /* DataTables Custom Styling */
        .dataTables_wrapper {
            @apply bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            @apply p-6 bg-emerald-50 dark:bg-slate-800 border-b border-emerald-100 dark:border-slate-700;
        }

        .dataTables_wrapper .dataTables_length select {
            @apply bg-white dark:bg-slate-700 text-emerald-800 dark:text-emerald-200 border border-emerald-200 dark:border-slate-600 rounded-lg px-3 py-2 mx-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500;
        }

        .dataTables_wrapper .dataTables_filter input {
            @apply bg-white dark:bg-slate-700 text-emerald-800 dark:text-emerald-200 border border-emerald-200 dark:border-slate-600 rounded-lg px-4 py-2 ml-2 w-64 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 placeholder-emerald-400 dark:placeholder-emerald-600;
        }

        .dataTables_wrapper table.dataTable {
            @apply border-collapse bg-white dark:bg-slate-800;
        }

        .dataTables_wrapper table.dataTable thead th {
            @apply bg-emerald-50 dark:bg-slate-700 text-emerald-800 dark:text-emerald-200 font-medium text-xs uppercase tracking-wider px-6 py-3 border-b-2 border-emerald-100 dark:border-slate-600;
        }

        .dataTables_wrapper table.dataTable tbody td {
            @apply px-6 py-4 text-sm text-slate-700 dark:text-slate-300 border-b border-emerald-50 dark:border-slate-700;
        }

        .dataTables_wrapper table.dataTable tbody tr {
            @apply hover:bg-emerald-50 dark:hover:bg-slate-700 transition-colors duration-150;
        }

        .dataTables_wrapper .dataTables_info {
            @apply p-2 text-sm text-emerald-600 dark:text-emerald-400;
        }

        .dataTables_wrapper .dataTables_paginate {
            @apply p-2 bg-emerald-50 dark:bg-slate-800 border-t border-emerald-100 dark:border-slate-700;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply px-3 py-1 text-sm text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-slate-700 rounded-md mx-1 transition-colors duration-150;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-emerald-500 text-white hover:bg-emerald-600 dark:bg-emerald-600 dark:hover:bg-emerald-700 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            @apply text-emerald-300 dark:text-emerald-800 hover:bg-transparent cursor-not-allowed;
        }
    </style>
    <script>
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const isClickInside = event.target.closest('.dropdown-toggle') || event.target.closest('#' + dropdownId);
                if (!isClickInside && dropdown && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            });
        }

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }
    </script>
</head>
<body class="bg-slate-100 dark:bg-slate-900 transition-colors duration-200">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="gradient-bg shadow-lg border-b border-emerald-800 dark:border-emerald-900 fixed top-0 left-0 right-0 z-50">
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
                                class="p-2 text-white hover:text-white hover:bg-emerald-600 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-400 transition-all duration-150">
                            <i class="fas fa-moon dark:hidden"></i>
                            <i class="fas fa-sun hidden dark:block"></i>
                        </button>
                        <!-- Trash Bin Status -->
                        <div class="relative">
                            <button onclick="toggleModal('trashBinModal')" 
                                    class="relative p-2 text-white hover:text-white hover:bg-emerald-600 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-400 transition-all duration-150">
                                <i class="fas fa-box text-xl"></i>
                            </button>
                        </div>
                        <!-- Download Report Button -->
                        <button onclick="generateUserReport()" 
                           class="inline-flex items-center px-4 py-2 bg-white text-emerald-800 text-sm font-medium rounded-lg hover:bg-emerald-50 transition-all duration-150 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i>
                            Generate Report
                        </button>
                        <!-- User Menu -->
                        <div class="relative">
                            <button onclick="toggleDropdown('userDropdown')" 
                                    class="dropdown-toggle flex items-center space-x-3 text-white hover:text-white focus:outline-none transition-colors duration-150">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-emerald-700">
                                    <i class="fas fa-user-circle text-white text-xl"></i>
                                </span>
                                <div class="flex items-center">
                                    <span class="text-sm font-medium mr-1">{{Auth::user()->name}}</span>
                                    <i class="fas fa-chevron-down text-sm text-white"></i>
                                </div>
                            </button>
                            <div id="userDropdown" class="dropdown-content hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl py-1 z-50 ring-1 ring-black ring-opacity-5">
                                <div class="px-4 py-2 border-b border-slate-200">
                                    <p class="text-sm font-medium text-slate-900">Admin Panel</p>
                                    <p class="text-xs text-slate-500 truncate">{{Auth::user()->email}}</p>
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
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="py-8 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Search and Filter Section -->
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" 
                                       id="deviceSearch" 
                                       placeholder="Search devices..." 
                                       class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <select id="statusFilter" class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="disconnected">Disconnected</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Metrics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Bottles/Cans Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-500 text-sm font-medium">Total Bottles & Cans</p>
                                <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-1">
                                    {{ $bottleStats['total'] ?? 0 }}
                                </p>
                            </div>
                            <div class="bg-emerald-100 dark:bg-emerald-900 p-3 rounded-xl">
                                <i class="fas fa-recycle text-2xl text-emerald-600 dark:text-emerald-500"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-emerald-600 dark:text-emerald-500 text-sm">
                            <i class="fas fa-chart-line mr-2"></i>
                            <span>Overall Collection</span>
                        </div>
                    </div>

                    <!-- Today's Bottles/Cans Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-500 text-sm font-medium">Today's Collection</p>
                                <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-1">
                                    {{ $bottleStats['today'] ?? 0 }}
                                </p>
                            </div>
                            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-xl">
                                <i class="fas fa-box text-2xl text-blue-600 dark:text-blue-500"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-blue-600 dark:text-blue-500 text-sm">
                            <i class="fas fa-clock mr-2"></i>
                            <span>Last 24 Hours</span>
                        </div>
                    </div>

                    <!-- Total Bandwidth Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-500 text-sm font-medium">Total Bandwidth</p>
                                <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-1">
                                    {{ $bandwidthStats['total'] ?? '0 B' }}
                                </p>
                            </div>
                            <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-xl">
                                <i class="fas fa-database text-2xl text-indigo-600 dark:text-indigo-500"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-indigo-600 dark:text-indigo-500 text-sm">
                            <i class="fas fa-wifi mr-2"></i>
                            <span>Data Served</span>
                        </div>
                    </div>

                    <!-- Today's Bandwidth Card
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-500 text-sm font-medium">Today's Bandwidth</p>
                                <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-1">
                                    {{ $bandwidthStats['today'] ?? '0 B' }}
                                </p>
                            </div>
                            <div class="bg-rose-100 dark:bg-rose-900 p-3 rounded-xl">
                                <i class="fas fa-bolt text-2xl text-rose-600 dark:text-rose-500"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-rose-600 dark:text-rose-500 text-sm">
                            <i class="fas fa-chart-area mr-2"></i>
                            <span>Current Usage</span>
                        </div>
                    </div> -->

                    <!-- Router Bandwidth Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-500 text-sm font-medium">Router Usage</p>
                                <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-1">
                                    {{ $routerBandwidth['total_rate'] }}
                                </p>
                            </div>
                            <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-xl">
                                <i class="fas fa-network-wired text-2xl text-purple-600 dark:text-purple-500"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm text-slate-500 mb-1">
                                <span>Download: {{ $routerBandwidth['rx_rate'] }}</span>
                                <span>Upload: {{ $routerBandwidth['tx_rate'] }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-bar-fill bg-purple-500" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Devices Table -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="p-6 bg-gradient-to-r from-slate-800 to-slate-700 dark:from-slate-900 dark:to-slate-800 border-b border-slate-700">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-white dark:text-slate-200">Connected Devices</h2>
                                <p class="mt-1 text-sm text-slate-300 dark:text-slate-500">Real-time device monitoring and statistics</p>
                            </div>
                            <div class="mt-4 sm:mt-0 flex space-x-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-500/20 dark:bg-emerald-500/20 text-emerald-300 dark:text-emerald-500 border border-emerald-500/20 dark:border-emerald-500/20">
                                    <i class="fas fa-circle text-xs mr-2 text-emerald-400 dark:text-emerald-500"></i>
                                    {{ $activeUsersCount }} Active
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table id="devicesTable" class="w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Device</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">MAC Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Bandwidth Used</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Last Seen</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse($devices as $device)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-slate-500 dark:text-slate-400">
                                            <i class="fas fa-laptop text-lg"></i>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-200">
                                        {{ $device->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ $device->mac_address }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $device->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $device->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ $device->bandwidth_used }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">
                                        No devices found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Trash Bin Modal -->
    <div id="trashBinModal" class="fixed inset-0 hidden z-50 flex items-center justify-center">
        <div class="modal-overlay absolute inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-xl max-w-lg w-full mx-4 shadow-2xl">
            <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white">Storage Status</h3>
                    <button onclick="toggleModal('trashBinModal')" class="text-slate-400 dark:text-slate-600 hover:text-slate-500 dark:hover:text-slate-500 transition-colors duration-150">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="px-4 py-4">
                <!-- Storage Status List -->
                <div class="space-y-4">
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-red-200 dark:border-red-700">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-wine-bottle text-red-600 dark:text-red-500"></i>
                                    <h4 class="text-sm font-semibold text-red-800 dark:text-red-200">Plastic Bottles Storage - Full</h4>
                                </div>
                                <p class="text-sm text-red-600 dark:text-red-500 mt-1">Last checked: January 26, 2025 01:32 AM</p>
                                <p class="text-xs text-red-500 dark:text-red-600 mt-1">Capacity: 1000 bottles (950 stored)</p>
                            </div>
                            <div class="flex items-center">
                                <div class="w-20 bg-red-200 dark:bg-red-700 rounded-full h-2 mr-2">
                                    <div class="bg-red-500 dark:bg-red-600 h-2 rounded-full" style="width: 95%"></div>
                                </div>
                                <span class="text-sm font-medium text-red-600 dark:text-red-500">95%</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-amber-200 dark:border-amber-700">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-can-food text-amber-600 dark:text-amber-500"></i>
                                    <h4 class="text-sm font-semibold text-amber-800 dark:text-amber-200">Cans Storage - Almost Full</h4>
                                </div>
                                <p class="text-sm text-amber-600 dark:text-amber-500 mt-1">Last checked: January 26, 2025 01:30 AM</p>
                                <p class="text-xs text-amber-500 dark:text-amber-600 mt-1">Capacity: 500 cans (400 stored)</p>
                            </div>
                            <div class="flex items-center">
                                <div class="w-20 bg-amber-200 dark:bg-amber-700 rounded-full h-2 mr-2">
                                    <div class="bg-amber-500 dark:bg-amber-600 h-2 rounded-full" style="width: 80%"></div>
                                </div>
                                <span class="text-sm font-medium text-amber-600 dark:text-amber-500">80%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-white dark:bg-slate-800 rounded-b-xl">
                <button onclick="toggleModal('trashBinModal')" 
                        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 dark:focus:ring-emerald-600 transition-all duration-150">
                    Close
                </button>
            </div>
        </div>
    </div>
</body>
<script>
    // Dark mode toggle functionality
    function toggleDarkMode() {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
    }

    // Check for saved dark mode preference
    if (localStorage.getItem('darkMode') === 'true' || 
        (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    }

    // Search and filter functionality
    const deviceSearch = document.getElementById('deviceSearch');
    const statusFilter = document.getElementById('statusFilter');
    const deviceTableBody = document.getElementById('deviceTableBody');

    function filterDevices() {
        const searchTerm = deviceSearch.value.toLowerCase();
        const statusTerm = statusFilter.value.toLowerCase();
        const rows = document.querySelectorAll('#deviceTableBody tr');

        rows.forEach(row => {
            if (row.cells.length > 3) { // Check if it's a valid device row
                const deviceName = row.cells[1].textContent.toLowerCase().trim();
                const deviceStatus = row.querySelector('td:nth-child(4) span').textContent.toLowerCase().trim();
                
                const matchesSearch = deviceName.includes(searchTerm);
                const matchesStatus = statusTerm === '' || deviceStatus === statusTerm;
                
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            }
        });
    }

    // Add event listeners when document is loaded
    document.addEventListener('DOMContentLoaded', function() {
        const deviceSearch = document.getElementById('deviceSearch');
        const statusFilter = document.getElementById('statusFilter');

        if (deviceSearch && statusFilter) {
            deviceSearch.addEventListener('input', filterDevices);
            statusFilter.addEventListener('change', filterDevices);
        }
    });

    $(document).ready(function() {
        $('#devicesTable').DataTable({
            responsive: true,
            pageLength: 10,
            dom: '<"flex flex-col sm:flex-row justify-between items-center gap-4 px-2"lf>rt<"flex flex-col sm:flex-row justify-between items-center gap-4"ip>',
            language: {
                search: "",
                searchPlaceholder: "Search devices...",
                lengthMenu: `<span class="text-emerald-600 dark:text-emerald-400">Show</span> _MENU_ <span class="text-emerald-600 dark:text-emerald-400">entries</span>`,
                info: `<span class="text-emerald-600 dark:text-emerald-400">Showing</span> _START_ <span class="text-emerald-600 dark:text-emerald-400">to</span> _END_ <span class="text-emerald-600 dark:text-emerald-400">of</span> _TOTAL_ <span class="text-emerald-600 dark:text-emerald-400">entries</span>`,
                paginate: {
                    first: '<i class="fas fa-angle-double-left"></i>',
                    last: '<i class="fas fa-angle-double-right"></i>',
                    next: '<i class="fas fa-angle-right"></i>',
                    previous: '<i class="fas fa-angle-left"></i>'
                }
            },
            drawCallback: function() {
                // Apply dark mode styles dynamically
                if (document.documentElement.classList.contains('dark')) {
                    $('.dataTables_wrapper').addClass('dark');
                }
            }
        });

        // Update DataTables styling when dark mode is toggled
        window.toggleDarkMode = function() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
            if (document.documentElement.classList.contains('dark')) {
                $('.dataTables_wrapper').addClass('dark');
            } else {
                $('.dataTables_wrapper').removeClass('dark');
            }
        };
    });

    function generateUserReport() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Add logo
        // const logoImg = new Image();
        // logoImg.src = "{{ asset('assets/img/logo/logo-green.png') }}";
        // doc.addImage(logoImg, 'PNG', 15, 15, 30, 30);
        
        // Title
        doc.setFontSize(20);
        doc.setTextColor(5, 150, 105); // Emerald color
        doc.text('WIFI FROM WASTE - User Report', 15, 25);
        
        // Date
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text(`Generated on: ${new Date().toLocaleString()}`, 15, 35);
        
        // User Information
        doc.setFontSize(14);
        doc.setTextColor(5, 150, 105);
        doc.text('User Information', 15, 50);
        
        doc.setFontSize(12);
        doc.setTextColor(60);
        doc.text(`Name: {{Auth::user()->name}}`, 15, 60);
        doc.text(`Email: {{Auth::user()->email}}`, 15, 70);
        doc.text(`Account Created: {{Auth::user()->created_at->format('F j, Y')}}`, 15, 80);
        
        // Connected Devices Section
        doc.setFontSize(14);
        doc.setTextColor(5, 150, 105);
        doc.text('Connected Devices Summary', 15, 100);
        
        // Create devices table
        const deviceData = [];
        const table = document.getElementById('devicesTable');
        if (table) {
            const rows = table.getElementsByTagName('tr');
            for (let i = 1; i < rows.length; i++) { // Skip header row
                const cells = rows[i].getElementsByTagName('td');
                if (cells.length > 0) {
                    deviceData.push([
                        cells[1].textContent.trim(), // Name
                        cells[2].textContent.trim(), // MAC Address
                        cells[3].textContent.trim(), // Status
                        cells[4].textContent.trim(), // Bandwidth
                        cells[5].textContent.trim()  // Last Seen
                    ]);
                }
            }
        }
        
        doc.autoTable({
            startY: 110,
            head: [['Device Name', 'MAC Address', 'Status', 'Bandwidth Used', 'Last Seen']],
            body: deviceData,
            theme: 'grid',
            headStyles: { fillColor: [5, 150, 105] },
            styles: { fontSize: 8 }
        });
        
        // Footer
        const pageCount = doc.internal.getNumberOfPages();
        doc.setFontSize(8);
        doc.setTextColor(100);
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.text(`Page ${i} of ${pageCount}`, doc.internal.pageSize.width - 20, doc.internal.pageSize.height - 10);
        }
        
        // Save the PDF
        doc.save(`wifi-from-waste-report-${new Date().toISOString().split('T')[0]}.pdf`);
    }
</script>
</html>
