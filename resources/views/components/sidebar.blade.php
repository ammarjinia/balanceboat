<aside
    class="fixed left-0 top-0 h-full w-72 bg-gradient-to-b from-purple-50 via-pink-50 to-green-50 border-r border-purple-200 flex flex-col">
    {{-- Logo --}}
    <div class="px-6 py-6 border-b border-purple-200">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                🧘
            </div>
            <span class="text-lg font-bold bg-gradient-to-r from-purple-700 to-pink-600 bg-clip-text text-transparent">
                BalanceBoat
            </span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-6 space-y-1">
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-medium text-sm transition-all duration-200 hover:bg-purple-100 @if (request()->routeIs('dashboard')) bg-purple-100 text-purple-900 @endif">
            <span class="text-xl">📊</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('account.show') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-medium text-sm transition-all duration-200 hover:bg-purple-100 @if (request()->routeIs('account.*')) bg-purple-100 text-purple-900 @endif">
            <span class="text-xl">👤</span>
            <span>Account</span>
        </a>

        <a href="{{ route('retreat.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-medium text-sm transition-all duration-200 hover:bg-purple-100 @if (request()->routeIs('retreat.*')) bg-purple-100 text-purple-900 @endif">
            <span class="text-xl">🏛️</span>
            <span>Retreats</span>
        </a>

        <a href="{{ route('booking.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 font-medium text-sm transition-all duration-200 hover:bg-purple-100 @if (request()->routeIs('booking.*')) bg-purple-100 text-purple-900 @endif">
            <span class="text-xl">📅</span>
            <span>Bookings</span>
        </a>
    </nav>

    {{-- Footer --}}
    <div class="px-4 py-4 border-t border-purple-200">
        <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-purple-200">
            <div
                class="w-9 h-9 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center text-white text-sm font-bold">
                {{ substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-gray-900 truncate">
                    {{ auth()->user()->primary_center?->name ?? 'No Center' }}
                </div>
                <div class="text-xs text-gray-500">Admin</div>
            </div>
        </div>
    </div>
</aside>
