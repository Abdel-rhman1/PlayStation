<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PlayStation Shop') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
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
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
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
        body {
            background-color: #020617;
        }
    </style>
</head>
<body class="antialiased text-white selection:bg-primary-500 selection:text-white">
    <!-- Language Switcher (Floating) -->
    <div class="fixed top-8 right-8 z-[100] @if(app()->getLocale() == 'ar') left-8 right-auto @endif">
        <div class="flex items-center gap-2 glass px-4 py-2 rounded-full border border-white/10 shadow-2xl">
            <a href="{{ route('lang.switch', 'en') }}" class="text-[10px] font-black uppercase tracking-widest {{ app()->getLocale() == 'en' ? 'text-primary-500' : 'text-white/40 hover:text-white' }} transition-colors">EN</a>
            <div class="w-px h-3 bg-white/10"></div>
            <a href="{{ route('lang.switch', 'ar') }}" class="text-[10px] font-black uppercase tracking-widest {{ app()->getLocale() == 'ar' ? 'text-primary-500' : 'text-white/40 hover:text-white' }} transition-colors">عربي</a>
        </div>
    </div>

    <div class="min-h-screen relative flex items-center justify-center p-6 overflow-hidden">
        <!-- Dynamic Background Elements -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-primary-600/15 blur-[120px] animate-pulse-slow"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-600/15 blur-[120px] animate-pulse-slow" style="animation-delay: 3s;"></div>
            <div class="absolute top-[20%] right-[10%] w-[30%] h-[30%] rounded-full bg-blue-500/10 blur-[100px]"></div>
        </div>

        <!-- Glass Container -->
        <div class="relative z-10 w-full max-w-[480px] animate-in fade-in zoom-in duration-1000">
            <div class="mb-10 text-center">
                <a href="/" class="group inline-flex flex-col items-center">
                    <div class="p-5 rounded-[2.2rem] bg-white/5 backdrop-blur-2xl border border-white/10 mb-6 shadow-2xl transition-all duration-500 group-hover:scale-110 group-hover:rotate-3 group-hover:bg-primary-500/20">
                        <svg class="w-10 h-10 text-primary-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.812 18.062c.187.188.438.281.688.281s.531-.094.719-.281c.375-.375.375-.969 0-1.344-2.188-2.188-2.188-5.75 0-7.938.375-.375.375-.969 0-1.344-.375-.375-.969-.375-1.344 0-2.906 2.938-2.906 7.688-.063 10.626zm18.376-10.626c-.375-.375-.969-.375-1.344 0-.375.375-.375.969 0 1.344 2.188 2.188 2.188 5.75 0 7.938-.375.375-.375.969 0 1.344.188.188.438.281.688.281s.531-.094.719-.281c2.906-2.938 2.906-7.688-.063-10.626z"/>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-heading font-black tracking-tighter bg-clip-text text-transparent bg-gradient-to-r from-white via-white to-white/40">
                        {{ config('app.name', 'PlayStation Shop') }}
                    </h1>
                    <p class="text-white/30 text-xs mt-3 font-bold uppercase tracking-[0.2em]">{{ __('auth.secure_access') }}</p>
                </a>
            </div>

            <div class="bg-white/[0.03] backdrop-blur-3xl border border-white/10 rounded-[3rem] p-10 md:p-12 shadow-2xl shadow-black/60 relative group overflow-hidden">
                <!-- Inner Glow -->
                <div class="absolute -top-32 -right-32 w-64 h-64 bg-primary-500/20 rounded-full blur-[80px] opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
                
                {{ $slot }}
            </div>
            
            <div class="mt-12 flex items-center justify-center gap-8">
                <p class="text-white/20 text-[10px] font-black tracking-widest uppercase italic">
                    &copy; {{ date('Y') }} {{ config('app.name') }}
                </p>
                <div class="h-px w-8 bg-white/10"></div>
                <a href="#" class="text-white/20 hover:text-white transition-colors text-[10px] font-black tracking-widest uppercase">{{ __('landing.privacy') }}</a>
                <a href="#" class="text-white/20 hover:text-white transition-colors text-[10px] font-black tracking-widest uppercase">{{ __('landing.terms') }}</a>
            </div>
        </div>
    </div>
</body>
</html>
