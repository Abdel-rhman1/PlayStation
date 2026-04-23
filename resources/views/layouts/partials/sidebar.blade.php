<aside class="fixed inset-y-0 start-0 z-50 w-72 bg-white border-e border-gray-200 transform lg:translate-x-0 transition-transform duration-300 ease-in-out"
       :class="sidebarOpen ? 'translate-x-0' : (document.documentElement.dir === 'rtl' ? 'translate-x-full' : '-translate-x-full')">
    
    <div class="flex flex-col h-full">
        <!-- Logo Section -->
        <div class="flex items-center justify-between h-20 px-6 border-b border-gray-50 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-200 text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm0-13a5 5 0 100 10 5 5 0 000-10z"/></svg>
                </div>
                <span class="text-xl font-heading font-bold text-gray-900 tracking-tight">PlayStation<span class="text-primary-600">Pro</span></span>
            </div>
            <button class="lg:hidden text-gray-400 hover:text-gray-600" @click="sidebarOpen = false">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Navigation Section -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            @php
                $navItems = [
                    ['name' => __('messages.dashboard'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'route' => route('dashboard')],
                    ['name' => __('devices.title'), 'icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z', 'route' => route('devices.index')],
                    ['name' => __('sessions.title'), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'route' => route('sessions.index')],
                    ['name' => __('pos.title'), 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'route' => route('pos.index')],
                    ['name' => __('reports.recent_orders'), 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'route' => route('orders.index')],
                    ['name' => __('pos.products'), 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'route' => route('products.index')],
                    ['name' => __('expenses.title'), 'icon' => 'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z', 'route' => route('expenses.index')],
                    ['name' => __('reports.title'), 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'route' => route('reports.index')],
                    ['name' => __('messages.settings'), 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M12 15a3 3 0 100-6 3 3 0 000 6z', 'route' => route('settings.index')],
                ];
            @endphp

            @foreach($navItems as $item)
                <a href="{{ $item['route'] }}" 
                   class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ Request::is(trim($item['route'], '/')) ? 'bg-primary-50 text-primary-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="me-3 h-5 w-5 {{ Request::is(trim($item['route'], '/')) ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                    </svg>
                    {{ $item['name'] }}
                </a>
            @endforeach
        </nav>

        <!-- Profile Brief Section -->
        <div class="p-6 border-t border-gray-50">
            <div class="bg-gray-50 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name ?? 'John Doe' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? 'john@example.com' }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>
