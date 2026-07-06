<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Center Panel — BalanceBoat')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    @yield('head')
    <style>
        [x-cloak] { display: none !important; }
        .glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(226, 232, 240, 0.85);
        }
        .card-hover {
            transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 40px -20px rgba(15, 23, 42, 0.18);
        }
        .glow-ai {
            box-shadow: 0 18px 60px -30px rgba(79, 70, 229, 0.35);
        }
        body {
            background: #f8fafc;
        }
    </style>
</head>
<body class="font-sans text-slate-800 bg-slate-50">
    <div id="view-app" class="min-h-screen flex flex-col lg:flex-row relative">
        <aside class="w-full lg:w-72 bg-white/90 border-b lg:border-b-0 lg:border-r border-slate-200 p-5 space-y-6 shrink-0 z-20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 px-3 mb-2">Operational Enclave</p>
                <div class="p-3 bg-white border border-slate-200 rounded-3xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="h-10 w-10 rounded-2xl bg-emerald-700 text-white flex items-center justify-center text-sm font-bold shadow-md">{{ strtoupper(substr($center->name ?? 'C', 0, 1)) }}</div>
                        <div class="truncate">
                            <p class="text-xs font-semibold text-slate-900 truncate">{{ $center->name ?? 'Center Name' }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ $center->city ?? 'Unknown Region' }}</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-up-down text-xs text-slate-400"></i>
                </div>
            </div>

            <nav class="space-y-1 max-h-[72vh] overflow-y-auto pr-1">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 px-3 mb-1.5">Management Elements Matrix</p>
                <a href="{{ route('center-panel.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-2xl text-xs transition-all {{ request()->routeIs('center-panel.dashboard') ? 'bg-gradient-to-r from-purple-50 to-orange-50 text-purple-700 font-semibold border border-purple-100/60 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                    <i class="fa-regular fa-chart-line text-sm w-4"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('center-panel.experiences') }}" class="flex items-center space-x-3 px-3 py-2 rounded-2xl text-xs transition-all {{ request()->routeIs('center-panel.experiences') ? 'bg-gradient-to-r from-purple-50 to-orange-50 text-purple-700 font-semibold border border-purple-100/60 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                    <i class="fa-regular fa-spa text-sm w-4 text-purple-600"></i>
                    <span>Retreat Management</span>
                </a>
                <a href="{{ route('center-panel.accommodations') }}" class="flex items-center space-x-3 px-3 py-2 rounded-2xl text-xs transition-all {{ request()->routeIs('center-panel.accommodations') || request()->routeIs('center-panel.accommodation.*') ? 'bg-gradient-to-r from-purple-50 to-orange-50 text-purple-700 font-semibold border border-purple-100/60 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                    <i class="fa-regular fa-bed text-sm w-4 text-purple-600"></i>
                    <span>Accommodations</span>
                </a>
                <a href="{{ route('center-panel.availability') }}" class="flex items-center space-x-3 px-3 py-2 rounded-2xl text-xs transition-all {{ request()->routeIs('center-panel.availability') || request()->routeIs('center-panel.availability.*') ? 'bg-gradient-to-r from-purple-50 to-orange-50 text-purple-700 font-semibold border border-purple-100/60 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                    <i class="fa-regular fa-calendar-days text-sm w-4 text-purple-600"></i>
                    <span>Availability & Pricing</span>
                </a>
                <a href="{{ route('center-panel.commission') }}" class="flex items-center space-x-3 px-3 py-2 rounded-2xl text-xs transition-all {{ request()->routeIs('center-panel.commission') || request()->routeIs('center-panel.commission.*') ? 'bg-gradient-to-r from-purple-50 to-orange-50 text-purple-700 font-semibold border border-purple-100/60 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                    <i class="fa-solid fa-percent text-sm w-4 text-purple-600"></i>
                    <span>Commission Engine</span>
                </a>
                <a href="{{ route('center-panel.bookings') }}" class="flex items-center space-x-3 px-3 py-2 rounded-2xl text-xs transition-all {{ request()->routeIs('center-panel.bookings') ? 'bg-gradient-to-r from-purple-50 to-orange-50 text-purple-700 font-semibold border border-purple-100/60 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                    <i class="fa-regular fa-calendar-check text-sm w-4"></i>
                    <span>Bookings Ledger</span>
                </a>
                <a href="{{ route('center-panel.settings') }}" class="flex items-center space-x-3 px-3 py-2 rounded-2xl text-xs transition-all {{ request()->routeIs('center-panel.settings') ? 'bg-gradient-to-r from-purple-50 to-orange-50 text-purple-700 font-semibold border border-purple-100/60 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                    <i class="fa-regular fa-user-gear text-sm w-4"></i>
                    <span>Center Profile</span>
                </a>
                <a href="{{ route('center-panel.logout') }}" class="flex items-center space-x-3 px-3 py-2 rounded-2xl text-xs text-slate-600 hover:text-slate-900 hover:bg-white/60 transition-all">
                    <i class="fa-solid fa-right-from-bracket text-sm w-4"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>

        <main class="flex-1 p-5 md:p-8 space-y-8 max-w-[1600px] mx-auto w-full overflow-x-hidden">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('userMenu');
            if (menu && !event.target.closest('button') && !event.target.closest('#userMenu')) {
                menu.classList.add('hidden');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
