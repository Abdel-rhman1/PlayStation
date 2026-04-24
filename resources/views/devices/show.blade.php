@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('devices.index') }}" class="p-2 bg-white rounded-xl border border-gray-100 text-gray-400 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ $device->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 rounded-full {{ $device->status->value === 'ON' ? 'bg-green-500 animate-pulse' : ($device->status->value === 'IN_USE' ? 'bg-primary-500' : 'bg-gray-400') }}"></span>
                    <span class="text-xs font-black uppercase text-gray-400 tracking-widest">{{ $device->status->value }}</span>
                    <span class="text-gray-200 mx-2">•</span>
                    <span class="text-xs font-bold text-gray-500">{{ $device->branch->name ?? __('devices.not_assigned') }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('devices.edit', $device) }}" class="px-6 py-3 bg-white border border-gray-100 rounded-2xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                {{ __('devices.edit_settings') }}
            </a>
            <form action="{{ route('devices.destroy', $device) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('{{ __('devices.decommission_confirm') }}')"
                        class="px-6 py-3 bg-red-50 text-red-600 rounded-2xl text-sm font-bold hover:bg-red-100 transition-all">
                    {{ __('devices.decommission') }}
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Stats Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm space-y-6">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('devices.rate_config') }}</label>
                    <div class="mt-4 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">{{ __('devices.hourly_rate') }}</span>
                            <span class="text-sm font-black text-gray-900">{{ __('messages.currency_symbol') }} {{ number_format($device->hourly_rate, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">{{ __('devices.startup_fee') }}</span>
                            <span class="text-sm font-black text-gray-900">{{ __('messages.currency_symbol') }} {{ number_format($device->fixed_rate, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-50">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('devices.network_info') }}</label>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs font-mono text-gray-400">IP: {{ $device->ip_address ?? __('devices.not_assigned') }}</span>
                        @if($device->ip_address)
                            <span class="px-2 py-1 rounded-md bg-green-50 text-[10px] font-black text-green-600">{{ __('devices.connected') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Session History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-heading font-black text-gray-900">{{ __('devices.recent_activity') }}</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-start">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('devices.operator') }}</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('devices.type') }}</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('devices.duration') }}</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-end">{{ __('devices.total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($device->sessions()->latest()->limit(10)->get() as $session)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <span class="text-sm font-bold text-gray-900">{{ $session->user->name ?? 'System' }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-2 py-1 rounded bg-gray-100 text-[10px] font-black uppercase text-gray-500 tracking-tighter">{{ $session->type }}</span>
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-500 font-medium">
                                    {{ $session->total_duration ?? '0' }} mins
                                </td>
                                <td class="px-8 py-5 text-end font-heading font-black text-gray-900">
                                    {{ __('messages.currency_symbol') }} {{ number_format($session->total_price, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center text-gray-400 italic">{{ __('devices.no_sessions') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
