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

    {{-- Commented out Today's Bandwidth Card - Uncomment if needed
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
    </div>
    --}}
</div>

<style>
    .metric-card {
        transition: all 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
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
</style>