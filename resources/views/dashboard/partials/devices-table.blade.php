<!-- Devices Table -->
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700">
    <div class="p-6 bg-gradient-to-r from-slate-800 to-slate-700 dark:from-slate-900 dark:to-slate-800 border-b border-slate-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-white dark:text-slate-200">Connected Devices</h2>
                <p class="mt-1 text-sm text-slate-300 dark:text-slate-500">Real-time device monitoring and statistics</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#3C8F3A]/20 dark:bg-[#3C8F3A]/20 text-[#3C8F3A] dark:text-[#3C8F3A] border border-[#3C8F3A]/20 dark:border-[#3C8F3A]/20">
                    <i class="fas fa-circle text-xs mr-2 text-emerald-400 dark:text-emerald-500"></i>
                    {{ $activeUsersCount }} Active
                </span>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
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
            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700" id="deviceTableBody">
                @forelse($devices as $device)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
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

<style>
    .table-row-hover:hover {
        background: linear-gradient(90deg, #f7fafc 0%, #edf2f7 100%);
    }
    .dark .table-row-hover:hover {
        background: linear-gradient(90deg, #1e293b 0%, #0f172a 100%);
    }
</style>

<script>
    // Search and filter functionality
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
</script>