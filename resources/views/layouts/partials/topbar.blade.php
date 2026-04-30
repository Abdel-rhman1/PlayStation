<header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-6 sticky top-0 z-30">
    <div class="flex items-center">
        <!-- Mobile Toggle -->
        <button class="lg:hidden p-2 -ms-2 text-gray-500 hover:text-gray-900" @click="sidebarOpen = true">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        
        <!-- Welcome Message -->
        <div class="hidden md:block ms-4">
            <h1 class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.welcome') }},</h1>
            <p class="text-lg font-heading font-bold text-gray-900">{{ auth()->user()->name ?? 'Administrator' }}</p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <!-- Search Bar (Desktop) -->
        <div class="hidden lg:flex items-center relative" x-data="{ 
            query: '', 
            results: [], 
            loading: false,
            search() {
                if(this.query.length < 2) { this.results = []; return; }
                this.loading = true;
                fetch(`/search?q=${this.query}`)
                    .then(res => res.json())
                    .then(data => {
                        this.results = data;
                        this.loading = false;
                    });
            }
        }">
            <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none text-gray-400">
                <svg x-show="!loading" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <svg x-show="loading" class="animate-spin h-4 w-4 text-primary-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>
            <input type="text" 
                   x-model="query" 
                   @input.debounce.300ms="search"
                   placeholder="{{ __('messages.search') }}" 
                   class="bg-gray-50 border-0 rounded-xl ps-10 pe-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 w-64 transition-all duration-200">
            
            <!-- Search Results Dropdown -->
            <div x-show="results.length > 0" 
                 x-cloak
                 class="absolute top-full start-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl ring-1 ring-gray-100 py-2 z-50 overflow-hidden">
                <template x-for="result in results" :key="result.url">
                    <a :href="result.url" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                        <p class="text-sm font-bold text-gray-900" x-text="result.title"></p>
                        <p class="text-[10px] text-gray-400 uppercase font-black" x-text="result.subtitle"></p>
                    </a>
                </template>
            </div>
        </div>

        <!-- Notifications -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-500 hover:bg-gray-100 transition-colors relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-primary-600 ring-2 ring-white"></span>
                @endif
            </button>

            <!-- Notifications Dropdown -->
            <div x-show="open" 
                 @click.away="open = false" 
                 x-cloak 
                 x-transition
                 class="absolute end-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl ring-1 ring-gray-100 overflow-hidden z-50">
                <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-black text-[10px] text-gray-900 uppercase tracking-[0.2em]">{{ __('messages.notifications') }}</h3>
                    <a href="{{ route('notifications.index') }}" class="text-[10px] font-black uppercase text-primary-600 hover:text-primary-700 transition-colors tracking-widest">
                        {{ __('messages.view') }}
                    </a>
                </div>
                <div class="divide-y divide-gray-50 max-h-96 overflow-y-auto">
                    @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                        <a href="{{ $notification->data['action_url'] ?? route('notifications.index') }}" class="p-4 hover:bg-gray-50 transition-colors flex gap-3 group">
                            <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0 {{ $notification->data['type'] == 'success' ? 'bg-green-500' : ($notification->data['type'] == 'warning' ? 'bg-yellow-500' : 'bg-primary-500') }}"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900 truncate">{{ $notification->data['title'] ?? 'System Update' }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-[10px] font-black uppercase text-gray-300 tracking-widest">No unread alerts</p>
                        </div>
                    @endforelse
                </div>
                
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-gray-50 text-[10px] font-black uppercase text-gray-400 hover:text-gray-900 transition-colors tracking-widest border-t border-gray-50">
                            {{ __('messages.mark_all_read') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Language Switcher -->
        <div class="flex items-center bg-gray-50 rounded-xl p-1">
            <a href="{{ route('lang.switch', 'en') }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all {{ app()->getLocale() == 'en' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                EN
            </a>
            <a href="{{ route('lang.switch', 'ar') }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all {{ app()->getLocale() == 'ar' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                AR
            </a>
        </div>

        <!-- User Dropdown (Alpine) -->
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button @click="open = !open" class="flex items-center gap-2 hover:bg-gray-50 p-1 rounded-xl transition-colors">
                <div class="w-10 h-10 rounded-xl object-cover bg-primary-600 flex items-center justify-center text-white font-heading font-semibold shadow-md shadow-primary-200">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
            </button>

            <div x-show="open" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute end-0 mt-2 w-56 rounded-2xl bg-white shadow-2xl ring-1 ring-gray-100 py-2 origin-top-right">
                
                <div class="px-4 py-3 border-b border-gray-50">
                    <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-1">Signed in as</p>
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                </div>
                
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <svg class="me-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ __('messages.profile') }}
                </a>
                
                <div class="border-t border-gray-50 mt-1"></div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left rtl:text-right flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <svg class="me-3 h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
