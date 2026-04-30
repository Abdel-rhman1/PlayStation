@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Page Header & Filters -->
    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('sessions.title') }}</h2>
                <p class="text-gray-500 text-sm">{{ __('sessions.subtitle') }}</p>
            </div>
            
                <form action="{{ url()->current() }}" method="GET" class="w-full flex flex-wrap items-center gap-3">
                    <!-- Modern Search Input -->
                    <div class="relative flex-1 min-w-[280px]">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="{{ __('sessions.search_placeholder') }}"
                               class="w-full bg-white border-gray-200 rounded-2xl pl-11 pr-4 py-3 text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all shadow-sm">
                        <div class="absolute left-4 top-3.5 text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>

                    <!-- Compact Selects -->
                    <div class="flex items-center gap-2">
                        <select name="device_id" class="bg-white border-gray-200 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 shadow-sm transition-all appearance-none cursor-pointer pr-10 relative">
                            <option value="">{{ __('devices.all_devices') ?? 'All Devices' }}</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>{{ $device->name }}</option>
                            @endforeach
                        </select>

                        <select name="status" class="bg-white border-gray-200 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 shadow-sm transition-all appearance-none cursor-pointer pr-10">
                            <option value="">{{ __('sessions.all_statuses') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('sessions.active') }}</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('sessions.completed') }}</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('sessions.cancelled') }}</option>
                        </select>
                    </div>

                    <!-- Date Range (Minimal) -->
                    <div class="hidden lg:flex items-center bg-white border border-gray-200 rounded-2xl px-4 py-1.5 shadow-sm">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mr-2">{{ __('sessions.date_range') }}</span>
                        <input type="date" name="from" value="{{ request('from') }}" class="border-none bg-transparent p-0 text-xs focus:ring-0 w-28">
                        <span class="text-gray-300 mx-2 text-xs">→</span>
                        <input type="date" name="to" value="{{ request('to') }}" class="border-none bg-transparent p-0 text-xs focus:ring-0 w-28">
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 ml-auto">
                        <button type="submit" class="bg-gray-900 text-white px-6 py-3 rounded-2xl text-sm font-bold hover:bg-black transition-all shadow-md">
                            {{ __('sessions.filter_logs') }}
                        </button>
                        
                        @if(request()->hasAny(['from', 'to', 'device_id', 'status', 'search']))
                            <a href="{{ url()->current() }}" class="w-11 h-11 flex items-center justify-center bg-gray-100 text-gray-400 rounded-2xl hover:bg-gray-200 hover:text-gray-600 transition-all" title="{{ __('sessions.clear') }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </a>
                        @endif
                    </div>
                </form>
        </div>
    </div>

    <!-- Sessions Table -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('devices.device_name') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('sessions.operator') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('sessions.duration') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">{{ __('sessions.cost') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">{{ __('messages.actions') ?? 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($sessions as $session)
                    <tr class="hover:bg-gray-50/50 transition-colors group cursor-pointer" onclick="window.location='{{ route('sessions.show', $session) }}'">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-primary-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 leading-none">{{ $session->device->name }}</p>
                                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-1">{{ __('sessions.id') }} {{ substr($session->id, 0, 8) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-6 h-6 rounded bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500">
                                    {{ substr($session->user->name ?? '?', 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-gray-700">{{ $session->user->name ?? __('messages.system') }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    <span class="text-xs font-bold text-gray-600">{{ $session->started_at->format('M d, H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                    <span class="text-xs font-medium text-gray-400 italic">
                                        {{ $session->ended_at ? $session->ended_at->format('M d, H:i') : __('sessions.active') . '...' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] font-black uppercase tracking-widest border border-gray-200">
                                {{ $session->ended_at ? $session->duration . ' ' . __('sessions.mins') : __('sessions.active') }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="text-lg font-heading font-black text-gray-900">
                                {{ __('messages.currency_symbol') }} {{ number_format($session->total_price, 2) }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('sessions.show', $session) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-black transition-all">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                {{ __('messages.view') ?? 'Details' }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="max-w-xs mx-auto space-y-4">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mx-auto">
                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-900">{{ __('sessions.no_sessions') }}</h4>
                                <p class="text-sm text-gray-400">{{ __('sessions.subtitle') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($sessions->hasPages())
        <div class="bg-gray-50/50 px-8 py-6 border-t border-gray-100">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
