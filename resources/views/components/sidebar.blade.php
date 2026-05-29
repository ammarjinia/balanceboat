<aside
    class="fixed left-0 top-0 w-72 h-screen bg-gradient-to-b from-purple-100 to-pink-50 border-r border-purple-200 p-6">
    {{-- Logo --}}
    <div class="flex items-center gap-3 mb-8 pb-6 border-b border-purple-200">
        <div
            class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center text-white text-xl">
            🧘
        </div>
        <span class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
            BalanceBoat
        </span>
    </div>

    {{-- Navigation --}}
    <nav class="space-y-2 flex-1">
        <a href="{{ route('dashboard.index') }}"
            class="block px-4 py-3 rounded-lg {{ request()->routeIs('dashboard.index') ? 'bg-white text-purple-600 font-semibold shadow' : 'text-gray-600 hover:bg-white/50' }}">
            <i class="fas fa-chart-line mr-3"></i>Dashboard
        </a>
        <a href="{{ route('retreat.index') }}"
            class="block px-4 py-3 rounded-lg {{ request()->routeIs('retreat.*') ? 'bg-white text-purple-600 font-semibold shadow' : 'text-gray-600 hover:bg-white/50' }}">
            <i class="fas fa-spa mr-3"></i>Retreats
        </a>
        <a href="{{ route('booking.index') }}"
            class="block px-4 py-3 rounded-lg {{ request()->routeIs('booking.*') ? 'bg-white text-purple-600 font-semibold shadow' : 'text-gray-600 hover:bg-white/50' }}">
            <i class="fas fa-calendar-check mr-3"></i>Bookings
        </a>
        <a href="{{ route('dashboard.account.show') }}"
            class="block px-4 py-3 rounded-lg {{ request()->routeIs('dashboard.account.*') ? 'bg-white text-purple-600 font-semibold shadow' : 'text-gray-600 hover:bg-white/50' }}">
            <i class="fas fa-user-cog mr-3"></i>Account
        </a>
    </nav>

    {{-- User Card --}}
    <div class="border-t border-purple-200 pt-4 mt-4">
        <div class="flex items-center gap-3 p-3 bg-white rounded-lg">
            <div
                class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center text-white text-sm font-bold">
                {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->full_name }}</p>
                <p class="text-xs text-gray-500">Admin</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit"
                class="w-full text-left px-4 py-2 text-gray-600 hover:bg-white/50 rounded-lg text-sm">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
        </form>
    </div>
</aside>
