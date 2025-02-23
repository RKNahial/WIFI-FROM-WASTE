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
    <div id="userDropdown" class="dropdown-content hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 z-50 ring-1 ring-black ring-opacity-5">
        <div class="px-4 py-2 border-b border-slate-200">
            <p class="text-sm font-medium text-slate-900">Admin Panel</p>
            <p class="text-xs text-slate-500">{{Auth::user()->email}}</p>
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