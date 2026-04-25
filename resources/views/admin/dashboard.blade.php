<x-app-layout>
    <div class="space-y-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-4xl font-heading font-black tracking-tighter text-gray-900">{{ __('admin.dashboard_title') }}</h1>
                <p class="text-gray-400 font-medium mt-2">{{ __('admin.dashboard_subtitle') }}</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('admin.reports') }}" class="px-8 py-4 bg-primary-600 rounded-[2rem] shadow-xl shadow-primary-200 font-bold text-white hover:bg-primary-700 hover:-translate-y-1 active:scale-95 transition-all flex items-center gap-3">
                    <div class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 17v-2a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2zm0-5V7a2 2 0 012-2h2a2 2 0 012 2v5a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    {{ __('admin.system_report') }}
                </a>
            </div>
        </div>

        <!-- Global Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Tenancies -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm ring-1 ring-black/5 hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-full">{{ __('Global') }}</span>
                </div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">{{ __('admin.total_tenancies') }}</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black text-gray-900 tracking-tight">{{ $totalTenants }}</h3>
                    <span class="text-green-500 text-xs font-bold">{{ $tenantGrowth >= 0 ? '+' : '' }}{{ number_format($tenantGrowth, 1) }}%</span>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm ring-1 ring-black/5 hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-black text-purple-600 uppercase tracking-widest bg-purple-50 px-3 py-1 rounded-full">{{ __('Network') }}</span>
                </div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">{{ __('admin.total_users') }}</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black text-gray-900 tracking-tight">{{ $totalUsers }}</h3>
                    <span class="text-green-500 text-xs font-bold">{{ $userGrowth >= 0 ? '+' : '' }}{{ number_format($userGrowth, 1) }}%</span>
                </div>
            </div>

            <!-- Global Revenue -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm ring-1 ring-black/5 hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 px-3 py-1 rounded-full">{{ __('Gross') }}</span>
                </div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">{{ __('admin.total_revenue') }}</p>
                <div class="flex items-baseline gap-2">
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black text-gray-900 tracking-tight">${{ number_format($totalRevenue, 2) }}</h3>
                    <span class="text-green-500 text-xs font-bold uppercase tracking-widest text-[8px]">{{ __('Live') }}</span>
                </div>
                </div>
            </div>

            <!-- Active Subscriptions -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm ring-1 ring-black/5 hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <span class="text-[10px] font-black text-rose-600 uppercase tracking-widest bg-rose-50 px-3 py-1 rounded-full">{{ __('SaaS') }}</span>
                </div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">{{ __('admin.active_subscriptions') }}</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black text-gray-900 tracking-tight">{{ $activeTenants }}</h3>
                    <span class="{{ $activeTenants > 0 ? 'text-green-500' : 'text-rose-500' }} text-xs font-bold uppercase tracking-widest text-[8px]">{{ $activeTenants > 0 ? __('Online') : __('Idle') }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Tenancy Growth -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('admin.recent_tenancies') }}</h2>
                    <a href="{{ route('admin.tenants') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 uppercase tracking-widest transition-colors">{{ __('admin.view_all_tenants') }}</a>
                </div>
                
                <div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-black/5 overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-50 bg-gray-50/30">
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.tenant_details') }}</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.plan_billing') }}</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.status') }}</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">{{ __('Created') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($latestTenants as $tenant)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-black text-gray-400 border border-black/5">
                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 leading-none">{{ $tenant->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1 tracking-widest">{{ $tenant->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 rounded-full bg-primary-50 text-primary-600 text-[10px] font-black uppercase tracking-widest">
                                        {{ $tenant->plan->name ?? 'Free' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $tenant->is_active ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                        <span class="text-xs font-bold {{ $tenant->is_active ? 'text-gray-900' : 'text-gray-400' }}">
                                            {{ $tenant->is_active ? __('admin.active_account') : __('admin.suspended_account') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <p class="text-sm font-bold text-gray-900">{{ $tenant->created_at->format('M d, Y') }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions & Alerts -->
            <div class="space-y-6">
                <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('admin.system_actions') }}</h2>
                
                <div class="space-y-4">
                    <a href="{{ route('admin.plans') }}" class="block p-8 rounded-[2.5rem] bg-gray-900 text-white shadow-xl hover:shadow-primary-500/20 hover:-translate-y-1 transition-all group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center group-hover:bg-primary-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            </div>
                        </div>
                        <h4 class="text-lg font-black tracking-tight mb-1">{{ __('admin.manage_plans') }}</h4>
                        <p class="text-white/40 text-xs font-medium">{{ __('admin.manage_plans_desc') }}</p>
                    </a>

                    <a href="{{ route('admin.reports') }}" class="block p-8 rounded-[2.5rem] border-2 border-dashed border-gray-200 hover:border-primary-500 hover:bg-primary-50/30 transition-all group">
                        <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:bg-primary-500 group-hover:text-white transition-all mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2zm0-5V7a2 2 0 012-2h2a2 2 0 012 2v5a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <h4 class="text-lg font-black tracking-tight text-gray-900 mb-1">{{ __('admin.system_report') }}</h4>
                        <p class="text-gray-400 text-xs font-medium">{{ __('admin.platform_audit') }}</p>
                    </a>

                    <div class="p-8 rounded-[2.5rem] border-2 border-dashed border-gray-200 hover:border-primary-200 transition-all cursor-pointer group">
                        <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:bg-primary-50 group-hover:text-primary-600 transition-all mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <h4 class="text-lg font-black tracking-tight text-gray-900 mb-1">{{ __('admin.add_feature') }}</h4>
                        <p class="text-gray-400 text-xs font-medium">{{ __('admin.add_feature_desc') }}</p>
                    </div>
                </div>

                <!-- Low Stock / Inactive Alerts -->
                <div class="bg-rose-50 p-8 rounded-[2rem] border border-rose-100">
                    <div class="flex items-center gap-3 text-rose-600 mb-4">
                        <svg class="w-6 h-6 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <h5 class="font-black uppercase tracking-widest text-[10px]">{{ __('admin.critical_alerts') }}</h5>
                    </div>
                    <ul class="space-y-3">
                        <li class="p-4 bg-white/60 rounded-2xl text-xs font-bold text-rose-900">
                            {{ __('admin.expiring_soon', ['count' => $expiringCount]) }}
                        </li>
                        <li class="p-4 bg-white/60 rounded-2xl text-xs font-bold text-rose-900">
                            {{ $totalTenants > 10 ? __('admin.db_optimization') : 'All systems healthy' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
