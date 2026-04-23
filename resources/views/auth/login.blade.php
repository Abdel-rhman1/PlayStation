<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | {{ config('app.name') }}</title>
    <!-- Tailwind & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="h-full antialiased overflow-hidden selection:bg-primary-500/30 selection:text-primary-200">

    <!-- Decorative Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-primary-600/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-blue-600/20 rounded-full blur-[120px]"></div>
    </div>

    <main class="relative h-full flex items-center justify-center p-6">
        <div class="w-full max-w-md space-y-12">
            <!-- Logo Section -->
            <div class="text-center space-y-4">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-primary-600 rounded-[2.5rem] shadow-2xl shadow-primary-500/50 rotate-3 transition-transform hover:rotate-0 duration-500">
                    <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-black text-white tracking-tighter">Control Hub.</h1>
                    <p class="text-gray-500 font-medium tracking-tight mt-2 italic">Access your PlayStation empire.</p>
                </div>
            </div>

            <!-- Login Form Card -->
            <div class="glass rounded-[3rem] p-10 shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-[1px] bg-gradient-to-r from-transparent via-primary-500 to-transparent"></div>

                <form method="POST" action="{{ route('login') }}" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-2">Email Identity</label>
                            <input type="email" name="email" required autofocus
                                   class="w-full bg-white/5 border border-white/5 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all placeholder:text-gray-700 font-bold"
                                   placeholder="admin@playstation.saas">
                            @error('email') <p class="text-[10px] font-bold text-red-500 uppercase mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-2">Secret Code</label>
                            <input type="password" name="password" required
                                   class="w-full bg-white/5 border border-white/5 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all placeholder:text-gray-700 font-bold"
                                   placeholder="••••••••">
                            @error('password') <p class="text-[10px] font-bold text-red-500 uppercase mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-2">
                        <label class="flex items-center gap-3 cursor-pointer group/check">
                            <input type="checkbox" name="remember" class="hidden peer">
                            <div class="w-5 h-5 rounded-lg border-2 border-white/10 flex items-center justify-center peer-checked:bg-primary-600 peer-checked:border-primary-600 transition-all">
                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-400 group-hover/check:text-gray-300 transition-colors">Keep me signed in</span>
                        </label>
                        <a href="#" class="text-xs font-black text-primary-500 hover:text-primary-400 transition-colors uppercase tracking-widest">Forgot?</a>
                    </div>

                    <button type="submit" 
                            class="w-full bg-primary-600 text-white font-black py-5 rounded-[1.5rem] shadow-xl shadow-primary-900/20 hover:bg-primary-500 hover:-translate-y-1 transition-all active:scale-95 duration-300">
                        Enter Dashboard
                    </button>
                </form>
            </div>

            <!-- Footer Section -->
            <p class="text-center text-sm text-gray-600 font-medium">
                New branch? <a href="{{ route('register') }}" class="text-white font-black hover:text-primary-500 transition-colors underline decoration-primary-500/30 underline-offset-4">Register here</a>
            </p>
        </div>
    </main>
</body>
</html>
