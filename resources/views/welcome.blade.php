<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - {{ __('landing.hero_title') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
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
        [x-cloak] { display: none !important; }
        body { background-color: #020617; }
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .text-gradient {
            background: linear-gradient(to right, #fff, rgba(255,255,255,0.5));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased text-white selection:bg-primary-500 selection:text-white">
    <!-- Background Effects -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-primary-600/10 blur-[120px] animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-600/10 blur-[120px] animate-pulse-slow" style="animation-delay: 3s;"></div>
    </div>

    <div class="relative z-10">
        <!-- Navbar -->
        <nav class="container mx-auto px-6 py-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-500 flex items-center justify-center shadow-lg shadow-primary-500/20">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M2.812 18.062c.187.188.438.281.688.281s.531-.094.719-.281c.375-.375.375-.969 0-1.344-2.188-2.188-2.188-5.75 0-7.938.375-.375.375-.969 0-1.344-.375-.375-.969-.375-1.344 0-2.906 2.938-2.906 7.688-.063 10.626zm18.376-10.626c-.375-.375-.969-.375-1.344 0-.375.375-.375.969 0 1.344 2.188 2.188 2.188 5.75 0 7.938-.375.375-.375.969 0 1.344.188.188.438.281.688.281s.531-.094.719-.281c2.906-2.938 2.906-7.688-.063-10.626z"/></svg>
                </div>
                <span class="text-xl font-heading font-black tracking-tight uppercase">{{ config('app.name') }}</span>
            </div>
            <div class="flex items-center gap-6">
                <!-- Language Switcher -->
                <div class="flex items-center gap-2 glass px-3 py-1.5 rounded-full border border-white/10">
                    <a href="{{ route('lang.switch', 'en') }}" class="text-[10px] font-black uppercase tracking-widest {{ app()->getLocale() == 'en' ? 'text-primary-500' : 'text-white/40 hover:text-white' }} transition-colors">EN</a>
                    <div class="w-px h-3 bg-white/10"></div>
                    <a href="{{ route('lang.switch', 'ar') }}" class="text-[10px] font-black uppercase tracking-widest {{ app()->getLocale() == 'ar' ? 'text-primary-500' : 'text-white/40 hover:text-white' }} transition-colors">عربي</a>
                </div>

                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="text-sm font-bold text-white/60 hover:text-white transition-colors">{{ __('landing.login') }}</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-6 py-3 rounded-full bg-white text-black text-sm font-bold hover:bg-gray-200 transition-all shadow-xl">{{ __('landing.get_started') }}</a>
                    @endif
                @endif
            </div>
        </nav>

        <!-- Hero -->
        <section class="container mx-auto px-6 py-20 text-center">
            <h1 class="text-5xl md:text-7xl font-heading font-black tracking-tighter mb-8 text-gradient">
                {{ __('landing.hero_title') }}
            </h1>
            <p class="max-w-2xl mx-auto text-lg text-white/50 mb-12 font-medium leading-relaxed">
                {{ __('landing.hero_subtitle') }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-primary-500 font-bold text-white hover:bg-primary-600 transition-all shadow-2xl shadow-primary-500/20">
                    {{ __('landing.start_free_trial') }}
                </a>
                <a href="#plans" class="w-full sm:w-auto px-8 py-4 rounded-2xl glass font-bold text-white hover:bg-white/5 transition-all text-sm uppercase tracking-widest">
                    {{ __('landing.view_pricing') }}
                </a>
            </div>
        </section>

        <!-- Dashboard Preview -->
        <section class="container mx-auto px-6 py-10">
            <div class="relative max-w-5xl mx-auto p-4 rounded-[2rem] glass shadow-2xl shadow-black/50 overflow-hidden transform hover:scale-[1.02] transition-transform duration-700">
                <div class="rounded-2xl overflow-hidden shadow-2xl border border-white/10 bg-[#0a0a0a]">
                    <img src="https://images.unsplash.com/photo-1612287230202-1ff1d85d1bdf?auto=format&fit=crop&q=80&w=2000" alt="Dashboard" class="w-full opacity-60">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-transparent to-transparent"></div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="glass p-8 rounded-full">
                         <svg class="w-12 h-12 text-primary-500 rtl:rotate-180" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- Plans Section -->
        <section id="plans" class="container mx-auto px-6 py-32">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-heading font-black mb-4">{{ __('landing.pricing_title') }}</h2>
                <p class="text-white/40 font-medium">{{ __('landing.pricing_subtitle') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @forelse($plans as $plan)
                <div class="group relative p-10 rounded-[2.5rem] glass transition-all duration-500 hover:bg-white/5 hover:-translate-y-2 {{ $plan->name == 'Pro' ? 'border-primary-500 shadow-2xl shadow-primary-500/10' : '' }}">
                    @if($plan->name == 'Pro')
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-primary-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full">
                        {{ __('landing.recommended') }}
                    </div>
                    @endif

                    <div class="mb-8">
                        <h3 class="text-2xl font-bold mb-2">{{ __('landing.'.strtolower($plan->name)) }}</h3>
                        <p class="text-white/40 text-sm font-medium">{{ __('landing.perfect_for_growing') }}</p>
                    </div>

                    <div class="flex items-baseline gap-2 mb-10">
                        <span class="text-5xl font-black">${{ $plan->price }}</span>
                        <span class="text-white/30 font-bold uppercase tracking-widest text-xs">/ {{ __('landing.month') }}</span>
                    </div>

                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center gap-3 text-sm font-medium">
                            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('landing.device_limit', ['limit' => $plan->device_limit]) }}</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm font-medium">
                            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('landing.sessions_logs') }}</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm font-medium text-white/50">
                            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('landing.pos_inventory') }}</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm font-medium text-white/50">
                            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('landing.reports') }}</span>
                        </li>
                    </ul>

                    <a href="{{ route('register', ['plan' => $plan->id]) }}" class="block w-full py-4 text-center rounded-2xl font-bold bg-white text-black hover:bg-primary-500 hover:text-white transition-all shadow-xl">
                        {{ __('landing.select_plan') }}
                    </a>
                </div>
                @empty
                <div class="col-span-1 md:col-span-3 text-center glass p-20 rounded-[3rem]">
                    <p class="text-white/40 font-bold uppercase tracking-widest">{{ __('No plans available at the moment. Please check back soon.') }}</p>
                </div>
                @endforelse
            </div>
        </section>

        <!-- Footer -->
        <footer class="container mx-auto px-6 py-20 border-t border-white/5 text-center">
            <p class="text-white/20 text-xs font-bold uppercase tracking-widest mb-6">{!! __('landing.footer_copy', ['year' => date('Y'), 'name' => config('app.name')]) !!}</p>
            <div class="flex items-center justify-center gap-8">
                <a href="#" class="text-white/30 hover:text-white transition-colors text-xs font-bold uppercase">{{ __('landing.privacy') }}</a>
                <a href="#" class="text-white/30 hover:text-white transition-colors text-xs font-bold uppercase">{{ __('landing.terms') }}</a>
                <a href="#" class="text-white/30 hover:text-white transition-colors text-xs font-bold uppercase">{{ __('landing.support') }}</a>
            </div>
        </footer>
    </div>
</body>
</html>
