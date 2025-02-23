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
        @include('layouts.partials.storage-status')
        <div class="px-4 py-3 bg-white dark:bg-slate-800 rounded-b-xl">
            <button onclick="toggleModal('trashBinModal')" 
                    class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-[#3C8F3A] hover:bg-[#2A632A] dark:bg-[#3C8F3A] dark:hover:bg-[#2A632A] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3C8F3A] dark:focus:ring-[#3C8F3A] transition-all duration-150">
                Close
            </button>
        </div>
    </div>
</div>