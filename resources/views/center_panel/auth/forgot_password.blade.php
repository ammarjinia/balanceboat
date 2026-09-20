<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — BalanceBoat Center</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    <style>
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
        body {
            background: radial-gradient(circle at 0% 0%, #f5f3ff 0%, #fff7ed 30%, #f0fdf4 70%, #ffffff 100%);
            background-attachment: fixed;
        }
        .error-message { color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem; }
    </style>
</head>
<body class="font-sans text-slate-800 antialiased min-h-screen overflow-x-hidden">

    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md space-y-8">
            <div class="flex items-center justify-center space-x-3">
                <div class="h-10 w-10 rounded-full bg-slate-900 flex items-center justify-center text-white shadow-lg">
                    <i class="fa-solid fa-building text-sm"></i>
                </div>
                <span class="font-serif text-2xl font-semibold tracking-wide text-slate-900">BalanceBoat</span>
            </div>

            <div class="glass-premium p-8 rounded-3xl shadow-xl space-y-6 relative border border-white glow-ai">
                <div class="space-y-1">
                    <h2 class="text-xl font-serif font-bold text-slate-900">Reset Your Password</h2>
                    <p class="text-xs text-slate-500">Enter your Center Owner email and we'll send you a link to reset your password.</p>
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

                <form method="POST" action="{{ route('center-panel.password.email') }}" class="space-y-4">
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

                    <button
                        type="submit"
                        class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold shadow-lg shadow-slate-900/10 transition-all flex items-center justify-center space-x-2"
                    >
                        <span>Send Reset Link</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <p class="text-center text-xs text-slate-500 pt-2">
                    <a href="{{ route('center-panel.login') }}" class="text-purple-600 font-bold hover:underline">
                        <i class="fa-solid fa-arrow-left text-[10px] mr-1"></i> Back to Sign In
                    </a>
                </p>
            </div>

            <div class="text-center text-[10px] text-slate-400 space-y-1">
                <p>&copy; 2026 BalanceBoat. All rights reserved.</p>
            </div>
        </div>
    </div>

</body>
</html>
