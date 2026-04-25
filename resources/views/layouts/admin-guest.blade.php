<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('auth.admin_access') }} | {{ config('app.name', 'PlayStation Shop') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa',
                            500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a',
                        },
                    },
                    animation: {
                        'pulse-slow': 'pulse 6s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>

    <style>
        body { background-color: #020617; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="antialiased text-white selection:bg-primary-500 selection:text-white">
    <!-- Language Switcher -->
    <div class="fixed top-8 right-8 z-[100] @if(app()->getLocale() == 'ar') left-8 right-auto @endif">
        <div class="flex items-center gap-2 glass px-4 py-2 rounded-full shadow-2xl">
            <a href="{{ route('lang.switch', 'en') }}" class="text-[10px] font-black uppercase tracking-widest {{ app()->getLocale() == 'en' ? 'text-primary-500' : 'text-white/40 hover:text-white' }} transition-colors">EN</a>
            <div class="w-px h-3 bg-white/10"></div>
            <a href="{{ route('lang.switch', 'ar') }}" class="text-[10px] font-black uppercase tracking-widest {{ app()->getLocale() == 'ar' ? 'text-primary-500' : 'text-white/40 hover:text-white' }} transition-colors">عربي</a>
        </div>
    </div>

    <div class="min-h-screen relative flex items-center justify-center p-6 overflow-hidden">
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-red-600/15 blur-[120px] animate-pulse-slow"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-primary-600/15 blur-[120px] animate-pulse-slow" style="animation-delay: 3s;"></div>
        </div>

        <div class="relative z-10 w-full max-w-[480px] animate-in fade-in zoom-in duration-1000">
            <div class="mb-10 text-center">
                <div class="inline-flex flex-col items-center">
                    <div class="p-5 rounded-[2.2rem] bg-white/5 backdrop-blur-2xl border border-white/10 mb-6 shadow-2xl transition-all duration-500 hover:scale-110 hover:bg-red-500/20">
                        <svg class="w-10 h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-heading font-black tracking-tighter bg-clip-text text-transparent bg-gradient-to-r from-white via-white to-white/40">
                        {{ __('auth.admin_access') }}
                    </h1>
                    <p class="text-red-500/50 text-xs mt-3 font-bold uppercase tracking-[0.2em]">{{ __('auth.admin_secure_access') }}</p>
                </div>
            </div>

            <div class="bg-white/[0.03] backdrop-blur-3xl border border-white/10 rounded-[3rem] p-10 md:p-12 shadow-2xl shadow-black/60 relative group overflow-hidden">
                <div class="absolute -top-32 -right-32 w-64 h-64 bg-red-500/10 rounded-full blur-[80px] opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
                {{ $slot }}
            </div>
            
            <p class="mt-8 text-center text-white/20 text-[10px] font-black tracking-widest uppercase">
                &copy; {{ date('Y') }} {{ config('app.name') }} System Control
            </p>
        </div>
    </div>
</body>
</html>
