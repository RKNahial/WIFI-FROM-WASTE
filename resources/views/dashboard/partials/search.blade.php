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
