@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-heading font-bold text-gray-900">{{ __('reports.title') }}</h2>
            <p class="text-gray-500 text-sm">{{ __('reports.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Apr 23, 2026
            </button>
            <a href="{{ route('devices.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-xl text-sm font-semibold text-white shadow-md shadow-primary-100 hover:bg-primary-700 transition-all">
                <svg class="w-4 h-4 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Session
            </a>
        </div>
    </div>

    <!-- Analytics Stat Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4" 
         x-data="{ 
            activeDevices: {{ $activeDevicesCount }},
            init() {
                if (window.Echo) {
                    window.Echo.private(`tenants.${window.tenantId}`)
                        .listen('DeviceTurnedOn', (e) => this.activeDevices++)
                        .listen('DeviceTurnedOff', (e) => this.activeDevices--);
                }
            }
         }">
        @php
            $analytics = [
                ['label' => __('reports.revenue'), 'value' => '$' . number_format($todayRevenue, 2), 'icon' => 'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z', 'color' => 'blue'],
                ['label' => __('reports.total_sessions'), 'value' => $totalSessionsToday, 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'purple'],
                ['label' => __('reports.active_devices'), 'value' => $activeDevicesCount, 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'green'],
                ['label' => __('expenses.title'), 'value' => '$4,200', 'icon' => 'M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'red'],
            ];
        @endphp

        @foreach($analytics as $item)
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-primary-100 transition-all duration-300">
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-{{ $item['color'] }}-50 flex items-center justify-center text-{{ $item['color'] }}-600 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ $item['label'] }}</p>
                    <p class="text-2xl font-heading font-black text-gray-900 tracking-tight" 
                       {!! $item['label'] === __('reports.active_devices') ? 'x-text="activeDevices"' : '' !!}>
                        {{ $item['value'] }}
                    </p>
                </div>
            </div>
            <div class="absolute -end-4 -bottom-4 w-20 h-20 bg-{{ $item['color'] }}-50/30 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        </div>
        @endforeach
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Sessions Table -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between px-2">
                <h3 class="text-xl font-heading font-bold text-gray-900">{{ __('sessions.title') }}</h3>
                <button class="text-sm font-bold text-primary-600 hover:underline">{{ __('reports.recent_orders') }}</button>
            </div>
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('sessions.operator') }} / {{ __('devices.device_name') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('sessions.duration') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-end">{{ __('sessions.cost') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentSessions as $session)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                                        {{ substr($session->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $session->user->name ?? 'Guest' }}</p>
                                        <p class="text-xs text-gray-400">{{ $session->device->name ?? 'Unknown Device' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1 rounded-full {{ $session->end_time ? 'bg-gray-100 text-gray-700' : 'bg-green-50 text-green-700' }} text-xs font-black uppercase tracking-tighter">
                                    {{ $session->end_time ? $session->duration . ' mins' : 'Active' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-end font-black text-gray-900">${{ number_format($session->total_price, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">No recent sessions found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Devices Leaderboard -->
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <h3 class="text-xl font-heading font-bold text-gray-900">{{ __('reports.top_devices') }}</h3>
                <span class="text-xs font-bold text-gray-400 uppercase">Revenue</span>
            </div>
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 space-y-6">
                @php
                    $topDevices = [
                        ['name' => 'PS5 Pro - VIP 1', 'revenue' => 450.00, 'usage' => 85, 'color' => 'primary'],
                        ['name' => 'PS5 Pro - Main 4', 'revenue' => 380.50, 'usage' => 72, 'color' => 'purple'],
                        ['name' => 'PS5 Pro - VIP 2', 'revenue' => 310.20, 'usage' => 64, 'color' => 'indigo'],
                        ['name' => 'PS5 Slim - Room 1', 'revenue' => 280.00, 'usage' => 58, 'color' => 'blue'],
                    ];
                @endphp

                @foreach($topDevices as $device)
                <div class="space-y-3 group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-{{ $device['color'] }}-500 group-hover:scale-150 transition-transform"></div>
                            <span class="font-bold text-gray-900">{{ $device['name'] }}</span>
                        </div>
                        <span class="text-sm font-black text-gray-900">${{ number_format($device['revenue'], 2) }}</span>
                    </div>
                    <div class="h-2.5 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-{{ $device['color'] }}-500 rounded-full transition-all duration-1000 ease-out" 
                             style="width: {{ $device['usage'] }}%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <span>Utilization</span>
                        <span>{{ $device['usage'] }}%</span>
                    </div>
                </div>
                @endforeach

                <button class="w-full mt-4 py-4 rounded-2xl bg-gray-50 text-sm font-bold text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-all border border-transparent hover:border-gray-200">
                    Detailed Analytics
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
