<aside class="fixed inset-y-0 start-0 z-50 w-72 bg-white border-e border-gray-200 transform lg:translate-x-0 transition-transform duration-300 ease-in-out"
       :class="sidebarOpen ? 'translate-x-0' : (document.documentElement.dir === 'rtl' ? 'translate-x-full' : '-translate-x-full')">
    
    <div class="flex flex-col h-full">
        <!-- Logo Section -->
        <div class="flex items-center justify-between h-20 px-6 border-b border-gray-50 flex-shrink-0 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-200 text-white group cursor-pointer transition-transform hover:scale-105 active:scale-95">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm0-13a5 5 0 100 10 5 5 0 000-10z"/></svg>
                </div>
                <span class="text-xl font-heading font-black text-gray-900 tracking-tight">PlayStation<span class="text-primary-600">Pro</span></span>
            </div>
            <button class="lg:hidden text-gray-400 hover:text-gray-600" @click="sidebarOpen = false">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Navigation Section -->
        <nav class="flex-1 px-4 py-8 space-y-8 overflow-y-auto custom-scrollbar">
            @php
                $sections = [
                    'Operations' => [
                        ['name' => __('messages.dashboard'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'route' => route('dashboard')],
                        ['name' => __('devices.title'), 'icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z', 'route' => route('devices.index')],
                        ['name' => __('sessions.title'), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'route' => route('sessions.index')],
                        ['name' => 'Shift Management', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'route' => route('shifts.index')],
                    ],
                    'Retail & Strategy' => [
                        ['name' => __('pos.title'), 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'route' => route('pos.index')],
                        ['name' => __('pos.products'), 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'route' => route('products.index')],
                        ['name' => __('reports.recent_orders'), 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'route' => route('orders.index')],
                    ],
                    'Administration' => [
                        ['name' => __('expenses.title'), 'icon' => 'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z', 'route' => route('expenses.index')],
                        ['name' => __('reports.title'), 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'route' => route('reports.index')],
                        ['name' => __('users.title'), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'route' => route('users.index')],
                        ['name' => __('roles.title') ?? 'Roles', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'route' => route('roles.index')],
                    ],
                ];
            @endphp

            @php $user = auth()->user(); @endphp

            {{-- Operations (all roles) --}}
            @if(!$user->isSuperAdmin())
            <div class="space-y-3">
                <h3 class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.operations') }}</h3>
                <div class="space-y-1">
                    @php
                        $ops = [
                            ['name' => __('messages.dashboard'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'route' => route('dashboard')],
                            ['name' => __('devices.title'), 'icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z', 'route' => route('devices.index')],
                            ['name' => __('sessions.title'), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'route' => route('sessions.index')],
                            ['name' => __('shifts.title_index'), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'route' => route('shifts.index')],
                        ];
                    @endphp
                    @foreach($ops as $item)
                        @php $isActive = Request::url() == $item['route'] || Str::startsWith(Request::url(), $item['route'] . '/'); @endphp
                        <a href="{{ $item['route'] }}"
                           class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ $isActive ? 'bg-primary-600 text-white shadow-lg shadow-primary-200' : 'text-gray-500 hover:bg-gray-50 hover:text-primary-600' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center me-3 transition-colors {{ $isActive ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-primary-50' }}">
                                <svg class="h-4 w-4 {{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-primary-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}" />
                                </svg>
                            </div>
                            {{ $item['name'] }}
                            @if($isActive)<div class="ms-auto w-1.5 h-1.5 rounded-full bg-white animate-pulse"></div>@endif
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Retail & Strategy (all roles) --}}
            @if(!$user->isSuperAdmin())
            <div class="space-y-3">
                <h3 class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.retail') }}</h3>
                <div class="space-y-1">
                    @php
                        $retail = [
                            ['name' => __('pos.title'), 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'route' => route('pos.index')],
                            ['name' => __('pos.products'), 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'route' => route('products.index')],
                            ['name' => __('reports.recent_orders'), 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'route' => route('orders.index')],
                        ];
                    @endphp
                    @foreach($retail as $item)
                        @php $isActive = Request::url() == $item['route'] || Str::startsWith(Request::url(), $item['route'] . '/'); @endphp
                        <a href="{{ $item['route'] }}"
                           class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ $isActive ? 'bg-primary-600 text-white shadow-lg shadow-primary-200' : 'text-gray-500 hover:bg-gray-50 hover:text-primary-600' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center me-3 transition-colors {{ $isActive ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-primary-50' }}">
                                <svg class="h-4 w-4 {{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-primary-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}" />
                                </svg>
                            </div>
                            {{ $item['name'] }}
                            @if($isActive)<div class="ms-auto w-1.5 h-1.5 rounded-full bg-white animate-pulse"></div>@endif
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Administration (Owner only) --}}
            @if(!$user->isSuperAdmin() && $user->isOwner())
            <div class="space-y-3">
                <h3 class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Administration</h3>
                <div class="space-y-1">
                    @php
                        $admin = [
                            ['name' => __('expenses.title'), 'icon' => 'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z', 'route' => route('expenses.index')],
                            ['name' => __('reports.title'), 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'route' => route('reports.index')],
                            ['name' => __('users.title'), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'route' => route('users.index')],
                            ['name' => __('roles.title') ?? 'Roles', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'route' => route('roles.index')],
                        ];
                    @endphp
                    @foreach($admin as $item)
                        @php $isActive = Request::url() == $item['route'] || Str::startsWith(Request::url(), $item['route'] . '/'); @endphp
                        <a href="{{ $item['route'] }}"
                           class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ $isActive ? 'bg-primary-600 text-white shadow-lg shadow-primary-200' : 'text-gray-500 hover:bg-gray-50 hover:text-primary-600' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center me-3 transition-colors {{ $isActive ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-primary-50' }}">
                                <svg class="h-4 w-4 {{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-primary-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}" />
                                </svg>
                            </div>
                            {{ $item['name'] }}
                            @if($isActive)<div class="ms-auto w-1.5 h-1.5 rounded-full bg-white animate-pulse"></div>@endif
                        </a>
                    @endforeach
                </div>
            </div>
            @endif


            {{-- Super Admin Management --}}
            @if($user->isSuperAdmin())
            <div class="space-y-6 pt-4 text-start">
                {{-- Platform Section --}}
                <div class="space-y-3">
                    <h3 class="px-4 text-[10px] font-black text-primary-600 uppercase tracking-[0.2em]">{{ __('messages.saas_control') }}</h3>
                    <div class="space-y-1">
                        @php
                            $platform = [
                                ['name' => __('messages.global_dashboard'), 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'route' => route('admin.dashboard')],
                                ['name' => __('messages.all_tenancies'), 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'route' => route('admin.tenants')],
                                ['name' => __('messages.pricing_plans'), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'route' => route('admin.plans')],
                            ];
                        @endphp
                        @foreach($platform as $item)
                            @php $isActive = Request::url() == $item['route'] || Str::startsWith(Request::url(), $item['route'] . '/'); @endphp
                            <a href="{{ $item['route'] }}"
                               class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ $isActive ? 'bg-primary-600 text-white shadow-lg shadow-primary-200' : 'text-gray-500 hover:bg-gray-50 hover:text-primary-600' }}">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center me-3 transition-colors {{ $isActive ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-primary-50' }}">
                                    <svg class="h-4 w-4 {{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-primary-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}" />
                                    </svg>
                                </div>
                                {{ $item['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Admin Section --}}
                <div class="space-y-3">
                    <h3 class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('Administration') }}</h3>
                    <div class="space-y-1">
                        @php
                            $management = [
                                ['name' => __('admin.manage_users'), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'route' => route('admin.users')],
                                ['name' => __('admin.manage_roles'), 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4', 'route' => route('admin.roles')],
                                ['name' => __('admin.system_report'), 'icon' => 'M9 17v-2a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2zm0-5V7a2 2 0 012-2h2a2 2 0 012 2v5a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'route' => route('admin.reports')],
                            ];
                        @endphp
                        @foreach($management as $item)
                            @php $isActive = Request::url() == $item['route'] || Str::startsWith(Request::url(), $item['route'] . '/'); @endphp
                            <a href="{{ $item['route'] }}"
                               class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ $isActive ? 'bg-primary-600 text-white shadow-lg shadow-primary-200' : 'text-gray-500 hover:bg-gray-50 hover:text-primary-600' }}">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center me-3 transition-colors {{ $isActive ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-primary-50' }}">
                                    <svg class="h-4 w-4 {{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-primary-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}" />
                                    </svg>
                                </div>
                                {{ $item['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- System Group (Separate Bottom) -->
            @if(!$user->isSuperAdmin())
            <div class="pt-4 border-t border-gray-50">
                <a href="{{ route('settings.index') }}" 
                   class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl text-gray-500 hover:bg-red-50 hover:text-red-600 transition-all">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center me-3 group-hover:bg-red-100 transition-colors">
                        <svg class="h-4 w-4 text-gray-400 group-hover:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924-1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M12 15a3 3 0 100-6 3 3 0 000 6z" />
                        </svg>
                    </div>
                    {{ __('messages.settings') }}
                </a>
            </div>
            @endif
        </nav>

        <!-- Profile Brief Section -->
        <div class="p-6 border-t border-gray-50 bg-gray-50/50">
            <div class="bg-white rounded-[1.5rem] p-4 flex items-center gap-4 shadow-sm ring-1 ring-black/5">
                <div class="relative">
                    <div class="w-12 h-12 rounded-xl bg-primary-600 flex items-center justify-center text-white font-black text-lg shadow-md shadow-primary-200">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-gray-900 truncate tracking-tight">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest truncate">{{ auth()->user()->role->name ?? 'Guest' }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
</style>
