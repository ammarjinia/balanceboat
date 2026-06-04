<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Center Login — BalanceBoat</title>
    <!-- Tailwind CSS CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    
    
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .glass-premium {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(245, 243, 255, 0.5) 100%);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 20px 40px -15px rgba(139, 92, 246, 0.05);
        }
        .glow-ai {
            box-shadow: 0 0 25px -2px rgba(139, 92, 246, 0.2), inset 0 0 12px rgba(255, 255, 255, 0.6);
        }
        .glow-peach { 
            box-shadow: 0 0 30px -5px rgba(249, 115, 22, 0.12); 
        }
        body {
            background: radial-gradient(circle at 0% 0%, #f5f3ff 0%, #fff7ed 30%, #f0fdf4 70%, #ffffff 100%);
            background-attachment: fixed;
        }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        .success-message {
            color: #16a34a;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body class="font-sans text-slate-800 antialiased min-h-screen overflow-x-hidden">

    <!-- MAIN LOGIN SECTION -->
    <div class="min-h-screen flex transition-all duration-500">
        <!-- Left Banner: Cinematic Wellness Storytelling -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900 items-center justify-center overflow-hidden p-12">
            <div class="absolute inset-0 bg-gradient-to-tr from-purple-900/50 via-slate-900/90 to-emerald-900/40 z-10"></div>
            <img src="https://images.unsplash.com/photo-1545205597-3d9d02c29597?auto=format&fit=crop&w=1200&q=80" alt="Meditation Sanctuary" class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-luminosity scale-105 animate-pulse" style="animation-duration: 12s;">
            
            <div class="relative z-20 max-w-lg space-y-8 text-white">
                <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full border border-white/20">
                    <i class="fa-solid fa-sparkles text-amber-300 text-xs animate-spin" style="animation-duration: 6s;"></i>
                    <span class="text-[10px] tracking-wider uppercase font-semibold">Center Management System</span>
                </div>
                <h1 class="text-5xl font-serif leading-tight">Manage your wellness sanctuary with ease.</h1>
                <p class="text-slate-300 font-light text-sm leading-relaxed">Access real-time bookings, manage experiences, track revenue, and optimize your center operations all in one secure platform.</p>
                <blockquote class="text-slate-400 font-light italic border-l-2 border-amber-300/60 pl-4 text-xs">
                    "BalanceBoat has simplified our center management exponentially."
                    <span class="block text-[10px] font-normal text-white uppercase tracking-wider not-italic mt-2">— Center Director, Bali</span>
                </blockquote>
            </div>
        </div>

        <!-- Right Side: Luxury Interactive Form Gateway -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 relative bg-white/30">
            <div class="w-full max-w-md space-y-8">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-slate-900 flex items-center justify-center text-white shadow-lg">
                        <i class="fa-solid fa-building text-sm"></i>
                    </div>
                    <div>
                        <span class="font-serif text-2xl font-semibold tracking-wide text-slate-900">BalanceBoat</span>
                    </div>
                </div>

                <div class="glass-premium p-8 rounded-3xl shadow-xl space-y-6 relative border border-white glow-ai">
                    <div class="space-y-1">
                        <h2 class="text-xl font-serif font-bold text-slate-900">Center Owner Access</h2>
                        <p class="text-xs text-slate-500">Sign in to your center management dashboard</p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 space-y-2">
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-600"><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                            <p class="text-sm text-green-600"><i class="fa-solid fa-check-circle mr-2"></i>{{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('center-panel.login.submit') }}" class="space-y-4">
                        @csrf
                        
                        <div class="space-y-1">
                            <label for="email" class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Email Address</label>
                            <input 
                                type="email" 
                                id="email"
                                name="email" 
                                required 
                                value="{{ old('email') }}"
                                class="w-full p-3 bg-white/80 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 outline-none transition-all font-medium @error('email') border-red-500 @enderror" 
                                placeholder="your.email@example.com"
                            >
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <div class="flex justify-between items-center">
                                <label for="password" class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Password</label>
                                <a href="" class="text-[10px] font-semibold text-purple-600 hover:underline">Forgot Password?</a>
                            </div>
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                required 
                                class="w-full p-3 bg-white/80 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 outline-none transition-all font-mono @error('password') border-red-500 @enderror" 
                                placeholder="••••••••••••"
                            >
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between text-xs py-1">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="remember" class="rounded border-slate-300 text-purple-600 focus:ring-purple-500/20 h-4 w-4">
                                <span class="text-slate-500 text-[11px]">Keep me logged in</span>
                            </label>
                            <span class="text-[11px] font-semibold text-slate-700 flex items-center">
                                <i class="fa-solid fa-shield-check text-emerald-600 mr-1"></i> Secure
                            </span>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold shadow-lg shadow-slate-900/10 transition-all flex items-center justify-center space-x-2"
                        >
                            <span>Access Center Dashboard</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </form>

                    <div class="relative flex py-2 items-center">
                        <div class="flex-grow border-t border-slate-200"></div>
                        <span class="flex-shrink mx-3 text-[10px] text-slate-400 font-bold uppercase tracking-widest">Questions?</span>
                        <div class="flex-grow border-t border-slate-200"></div>
                    </div>

                    <p class="text-center text-xs text-slate-500 pt-2">
                        Don't have access yet? 
                        <a href="mailto:support@balanceboats.com" class="text-purple-600 font-bold hover:underline">Contact Support</a>
                    </p>
                </div>

                <div class="text-center text-[10px] text-slate-400 space-y-1">
                    <p>© 2026 BalanceBoat. All rights reserved.</p>
                    <p><a href="#" class="hover:underline">Privacy Policy</a> | <a href="#" class="hover:underline">Terms of Service</a></p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
