<div class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
    <div class="flex-1"></div>
    <div class="flex items-center gap-6">
        <button class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-bell text-lg"></i>
        </button>
        <div class="relative group">
            <button class="flex items-center gap-2 text-gray-900 hover:text-purple-600">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name) }}" alt="Avatar"
                    class="w-8 h-8 rounded-lg">
                <span class="text-sm font-medium">{{ auth()->user()->first_name }}</span>
            </button>
            <div
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                <a href="{{ route('account.show') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
