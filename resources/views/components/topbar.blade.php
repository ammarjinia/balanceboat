<header class="bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-40">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">@yield('page_title', 'Dashboard')</h1>
        <div class="flex items-center gap-4">
            <button class="p-2 hover:bg-gray-100 rounded-lg relative">
                <i class="fas fa-bell text-gray-600"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            <div
                class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-lg flex items-center justify-center text-white">
                {{ substr(Auth::user()->first_name, 0, 1) }}
            </div>
        </div>
    </div>
</header>
