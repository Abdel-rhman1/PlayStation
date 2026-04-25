<aside x-show="true" 
       :class="sidebarOpen ? 'translate-x-0' : (appLocale === 'ar' ? 'translate-x-full' : '-translate-x-full')"
       class="fixed inset-y-0 start-0 z-50 w-72 bg-slate-900 border-e border-white/5 transition-transform duration-300 lg:translate-x-0">
    
    <div class="h-full flex flex-col p-6">
        <!-- Logo Area -->
        <div class="mb-10 px-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center text-white shadow-lg shadow-red-900/40 font-black">
                    S
                </div>
                <div>
                    <h2 class="text-white font-heading font-black tracking-tighter leading-none">{{ __('admin.system_admin') }}</h2>
                    <p class="text-white/30 text-[10px] font-black uppercase tracking-widest mt-1">{{ __('admin.system_control') }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 space-y-8 overflow-y-auto custom-scrollbar">
            <div class="space-y-1">
                <p class="px-4 text-[10px] font-black text-white/20 uppercase tracking-[0.2em] mb-4">{{ __('admin.core_management') }}</p>
                
                @php
                    $nav = [
                        ['name' => __('messages.global_dashboard'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'route' => route('admin.dashboard')],
                        ['name' => __('messages.all_tenancies'), 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'route' => route('admin.tenants')],
                        ['name' => __('messages.pricing_plans'), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'route' => route('admin.plans')],
                    ];
                @endphp

                @foreach($nav as $item)
                @php $isActive = Request::url() == $item['route'] || Str::startsWith(Request::url(), $item['route'] . '/'); @endphp
                <a href="{{ $item['route'] }}" 
                   class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all {{ $isActive ? 'bg-red-600 text-white shadow-lg shadow-red-900/40' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 me-3 {{ $isActive ? 'text-white' : 'text-white/20 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                    </svg>
                    {{ $item['name'] }}
                </a>
                @endforeach
            </div>

            <div class="space-y-1">
                <p class="px-4 text-[10px] font-black text-white/20 uppercase tracking-[0.2em] mb-4">{{ __('admin.users_security') }}</p>
                <a href="{{ route('admin.users') }}" class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl text-white/50 hover:bg-white/5 hover:text-white transition-all">
                    <svg class="h-5 w-5 me-3 text-white/20 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    {{ __('admin.all_users') }}
                </a>
                <a href="{{ route('admin.roles') }}" class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl text-white/50 hover:bg-white/5 hover:text-white transition-all">
                    <svg class="h-5 w-5 me-3 text-white/20 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    {{ __('admin.manage_roles') }}
                </a>
            </div>

            <div class="space-y-1">
                <p class="px-4 text-[10px] font-black text-white/20 uppercase tracking-[0.2em] mb-4">{{ __('admin.advanced_analytics') }}</p>
                <a href="{{ route('admin.reports') }}" class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl text-white/50 hover:bg-white/5 hover:text-white transition-all">
                    <svg class="h-5 w-5 me-3 text-white/20 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    {{ __('admin.growth_reports') }}
                </a>
            </div>
        </nav>

        <!-- Profile Link -->
        <div class="mt-auto pt-6 border-t border-white/5">
            <div class="flex items-center gap-3 px-4">
                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-red-500 font-black">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs font-bold text-white">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-white/30 font-bold uppercase tracking-widest mt-0.5">{{ __('admin.admin') }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>
