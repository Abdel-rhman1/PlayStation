<div x-data="deviceManager('{{ $device->id }}', '{{ $device->status->value ?? $device->status }}', {{ $device->hourly_rate ?? 0 }}, {{ $device->fixed_rate ?? 0 }}, '{{ $device->activeSession->started_at ?? '' }}')"
     class="group relative bg-white rounded-[2.5rem] p-1.5 border border-gray-100 shadow-sm hover:shadow-2xl hover:shadow-primary-100/50 transition-all duration-500 overflow-hidden"
     :class="{
        'ring-4 ring-yellow-400/20 bg-yellow-50/5': hasSession,
        'ring-4 ring-green-400/20 bg-green-50/5': !hasSession && status === 'ON',
        'border-gray-200 grayscale-[0.5]': status === 'OFF'
     }">
    
    <!-- Status Glow Background -->
    <div class="absolute -top-24 -right-24 w-48 h-48 rounded-full blur-[80px] opacity-0 group-hover:opacity-100 transition-opacity duration-1000"
         :class="{
            'bg-yellow-400/20': hasSession,
            'bg-green-400/20': !hasSession && status === 'ON',
            'bg-gray-400/10': status === 'OFF'
         }"></div>

    <div class="relative z-10 bg-white rounded-[2.25rem] p-6 h-full flex flex-col">
        <!-- Header -->
        <div class="flex items-start justify-between mb-8">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center transition-all duration-500 shadow-sm border border-gray-50"
                 :class="{
                    'bg-yellow-400 text-white shadow-yellow-200 rotate-3': hasSession,
                    'bg-gray-900 text-white shadow-gray-200': !hasSession && status === 'ON',
                    'bg-gray-50 text-gray-400': status === 'OFF'
                 }">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            
            <div class="flex flex-col items-end gap-3">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm transition-all duration-500"
                     :class="{
                        'bg-yellow-400 text-white animate-pulse': hasSession,
                        'bg-green-500 text-white': !hasSession && status === 'ON',
                        'bg-gray-200 text-gray-500': status === 'OFF'
                     }">
                    <span class="w-1.5 h-1.5 rounded-full bg-white shadow-sm" :class="hasSession ? 'animate-ping' : ''"></span>
                    <span x-text="hasSession ? '{{ __('devices.in_use') }}' : (status === 'ON' ? '{{ __('devices.connected') }}' : 'OFF')"></span>
                </div>
                
                <div class="flex items-center gap-1 bg-gray-50 p-1 rounded-xl border border-gray-100">
                    <a href="{{ route('devices.show', $device) }}" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-white hover:shadow-sm rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    <a href="{{ route('devices.edit', $device) }}" class="p-2 text-gray-400 hover:text-gray-900 hover:bg-white hover:shadow-sm rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="mb-8">
            <h3 class="text-xl font-heading font-black text-gray-900 tracking-tight leading-tight group-hover:text-primary-600 transition-colors">{{ $device->name }}</h3>
            <div class="flex items-center gap-2 mt-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 bg-gray-50 px-2 py-1 rounded-md">{{ $device->branch->name ?? 'Main' }}</span>
                <span class="text-gray-200">•</span>
                <span class="text-[10px] font-black uppercase tracking-widest text-primary-500">${{ $device->hourly_rate }}/hr</span>
            </div>
        </div>

        <!-- Stats Block (Active Only) -->
        <div class="mt-auto">
            <div x-show="hasSession" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="grid grid-cols-2 gap-4 py-6 border-y border-gray-50 mb-6">
                <div class="space-y-1">
                    <p class="text-[9px] text-gray-400 uppercase font-black tracking-widest">{{ __('sessions.elapsed') }}</p>
                    <p class="text-xl font-heading font-black text-gray-900 tracking-widest tabular-nums" x-text="timerDisplay">00:00:00</p>
                </div>
                <div class="space-y-1 text-end">
                    <p class="text-[9px] text-gray-400 uppercase font-black tracking-widest">{{ __('sessions.cost') }}</p>
                    <div class="flex items-center justify-end gap-1">
                        <span class="text-xs font-bold text-primary-500">$</span>
                        <span class="text-2xl font-heading font-black text-gray-900 tabular-nums" x-text="costDisplay">0.00</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <form action="{{ route('devices.sessions.start', $device) }}" method="POST" class="flex-1" x-show="!hasSession">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-black hover:shadow-xl hover:shadow-gray-200 active:scale-95 transition-all text-sm uppercase tracking-widest">
                        {{ __('sessions.start_session') }}
                    </button>
                </form>
                
                <form action="{{ route('devices.sessions.stop', $device) }}" method="POST" class="flex-1" x-show="hasSession">
                    @csrf
                    <button type="submit" 
                            @click.prevent="askConfirm('Finalize Charge?', 'This will calculate the final bill for {{ $device->name }}.', () => $el.closest('form').submit())"
                            class="w-full bg-red-500 text-white font-black py-4 rounded-2xl hover:bg-red-600 hover:shadow-xl hover:shadow-red-200 active:scale-95 transition-all text-sm uppercase tracking-widest">
                        {{ __('sessions.stop_session') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Decorative Corner Element -->
    <div class="absolute -bottom-6 -right-6 w-12 h-12 bg-gray-50 rounded-full group-hover:scale-[3] transition-transform duration-700 opacity-50"></div>
</div>

