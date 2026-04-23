@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="{ 
    showCreateModal: false, 
    searchQuery: '',
    statusFilter: 'all',
    activeCount: {{ $activeDevicesCount }},
    totalCount: {{ $devices->count() }}
}">
    <!-- Top Hero Section -->
    <div class="relative overflow-hidden bg-gray-900 rounded-[3rem] p-8 md:p-12 text-white">
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-12 w-64 h-64 bg-primary-500/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 translate-y-12 -translate-x-12 w-64 h-64 bg-purple-500/10 rounded-full blur-[100px]"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <h2 class="text-4xl font-heading font-black tracking-tight">{{ __('devices.title') }}</h2>
                    <div class="px-3 py-1 bg-white/10 rounded-full border border-white/10 backdrop-blur-md">
                        <span class="text-xs font-black uppercase tracking-widest text-primary-400">{{ __('devices.live') }}</span>
                    </div>
                </div>
                <p class="text-gray-400 max-w-md font-medium">{{ __('devices.subtitle') }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <!-- Stats Summary -->
                <div class="flex gap-4">
                    <div class="bg-white/5 border border-white/10 rounded-[2rem] px-6 py-4 backdrop-blur-md">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">{{ __('devices.active') }}</p>
                        <p class="text-2xl font-heading font-black text-white" x-text="activeCount">{{ $activeDevicesCount }}</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-[2rem] px-6 py-4 backdrop-blur-md">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">{{ __('devices.total') }}</p>
                        <p class="text-2xl font-heading font-black text-white" x-text="totalCount">{{ $devices->count() }}</p>
                    </div>
                </div>

                <button @click="showCreateModal = true" 
                        class="h-16 px-8 bg-primary-500 hover:bg-primary-600 rounded-[1.5rem] flex items-center gap-3 font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-xl shadow-primary-500/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    {{ __('devices.add_device') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Create Device Modal -->
    <div x-show="showCreateModal" 
         x-cloak
         class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition opacity-0 duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300"
             @click.away="showCreateModal = false">
            
            <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h3 class="text-2xl font-heading font-black text-gray-900">{{ __('devices.new_hardware') }}</h3>
                    <p class="text-sm text-gray-400 font-medium">{{ __('devices.subtitle') }}</p>
                </div>
                <button @click="showCreateModal = false" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-400 hover:text-gray-900 shadow-sm transition-all">&times;</button>
            </div>

            <form action="{{ route('devices.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('devices.device_name') }}</label>
                        <input type="text" name="name" required placeholder="e.g. PS5 - VIP Room 1" 
                               class="w-full bg-gray-50 border-transparent rounded-2xl px-4 py-4 focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all font-bold text-gray-900 shadow-inner">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('devices.branch') }}</label>
                        <select name="branch_id" required 
                                class="w-full bg-gray-50 border-transparent rounded-2xl px-4 py-4 focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all font-bold text-gray-900 shadow-inner">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('devices.ip_address') }}</label>
                        <input type="text" name="ip_address" placeholder="192.168.1.50" 
                               class="w-full bg-gray-50 border-transparent rounded-2xl px-4 py-4 focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all font-bold text-gray-900 shadow-inner">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('devices.hourly_rate') }} ($)</label>
                        <input type="number" step="0.01" name="hourly_rate" required placeholder="10.00" 
                               class="w-full bg-gray-50 border-transparent rounded-2xl px-4 py-4 focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all font-bold text-gray-900 text-center shadow-inner">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('devices.fixed_rate') }} ($)</label>
                        <input type="number" step="0.01" name="fixed_rate" value="0.00" 
                               class="w-full bg-gray-50 border-transparent rounded-2xl px-4 py-4 focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all font-bold text-gray-900 text-center shadow-inner">
                    </div>
                </div>

                <input type="hidden" name="status" value="OFF">

                <div class="flex gap-4 pt-4">
                    <button type="button" @click="showCreateModal = false" 
                            class="flex-1 py-5 rounded-2xl bg-gray-100 font-bold text-gray-500 hover:bg-gray-200 transition-all">{{ __('messages.cancel') }}</button>
                    <button type="submit" 
                            class="flex-1 py-5 rounded-2xl bg-gray-900 font-black text-white shadow-xl shadow-gray-200 hover:bg-black transition-all active:scale-95">{{ __('devices.register') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="flex items-center gap-4 bg-white p-2 rounded-2xl shadow-sm border border-gray-100 w-full md:w-auto">
            <button @click="statusFilter = 'all'" 
                    class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all"
                    :class="statusFilter === 'all' ? 'bg-gray-900 text-white shadow-lg' : 'text-gray-400 hover:text-gray-900'">
                All
            </button>
            <button @click="statusFilter = 'IN_USE'" 
                    class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all"
                    :class="statusFilter === 'IN_USE' ? 'bg-yellow-400 text-white shadow-lg shadow-yellow-100' : 'text-gray-400 hover:text-gray-900'">
                In Use
            </button>
            <button @click="statusFilter = 'ON'" 
                    class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all"
                    :class="statusFilter === 'ON' ? 'bg-green-500 text-white shadow-lg shadow-green-100' : 'text-gray-400 hover:text-gray-900'">
                Available
            </button>
        </div>

        <div class="relative w-full md:w-80">
            <input type="text" x-model="searchQuery" placeholder="Search devices by name or branch..." 
                   class="w-full bg-white border-transparent rounded-2xl px-6 py-4 shadow-sm focus:ring-2 focus:ring-primary-500 transition-all font-bold text-gray-900 text-sm">
            <svg class="absolute right-6 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>

    <!-- Devices Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($devices as $device)
            <div x-show="(searchQuery === '' || '{{ strtolower($device->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($device->branch->name ?? '') }}'.includes(searchQuery.toLowerCase())) && (statusFilter === 'all' || '{{ $device->status->value }}' === statusFilter || (statusFilter === 'IN_USE' && {{ $device->activeSession ? 'true' : 'false' }}))"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                @include('devices.partials.device-card')
            </div>
        @empty
            <div class="col-span-full py-32 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100 animate-in fade-in zoom-in duration-700">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-xl font-heading font-black text-gray-900">No hardware detected</h3>
                <p class="text-gray-400 mt-2">Start by registering your first device to this branch.</p>
                <button @click="showCreateModal = true" class="mt-8 px-8 py-4 bg-gray-900 text-white rounded-2xl font-bold shadow-xl hover:bg-black transition-all">Add Device Now</button>
            </div>
        @endforelse
    </div>
</div>
</div>

@endsection
