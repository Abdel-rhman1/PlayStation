<x-admin-layout>
    <div class="space-y-12">
        <!-- Modern Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="px-3 py-1 bg-red-600 rounded-full text-[10px] font-black text-white uppercase tracking-widest">
                        {{ __('admin.system_intelligence') }}
                    </div>
                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                </div>
                <h1 class="text-5xl font-heading font-black tracking-tighter text-slate-900">{{ __('admin.system_report') }}</h1>
                <p class="text-slate-400 font-medium max-w-xl">{{ __('admin.platform_audit') }} - {{ __('admin.realtime_metrics') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="window.print()" class="group px-8 py-4 bg-slate-900 rounded-3xl shadow-2xl shadow-slate-200 font-bold text-white hover:bg-black hover:-translate-y-1 active:scale-95 transition-all flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>{{ __('admin.print_report') }}</span>
                </button>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-4">{{ __('admin.gross_mrr') }}</p>
                <div class="flex items-baseline gap-3">
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight">${{ number_format($revenueByMonth->sum('total'), 2) }}</h2>
                    <span class="text-xs font-bold text-green-500">+12.5%</span>
                </div>
                <div class="mt-6 h-1 w-full bg-slate-50 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 w-[65%] group-hover:w-[70%] transition-all duration-1000"></div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-4">{{ __('admin.projected_annual') }}</p>
                <div class="flex items-baseline gap-3">
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight">${{ number_format($projectedRevenue * 12, 2) }}</h2>
                </div>
                <div class="mt-6 h-1 w-full bg-slate-50 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 w-[45%] group-hover:w-[55%] transition-all duration-1000"></div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-4">{{ __('admin.subscriber_ltv') }}</p>
                <div class="flex items-baseline gap-3">
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight">${{ number_format(($revenueByMonth->sum('total') / max($tenants->total(), 1)) * 12, 2) }}</h2>
                </div>
                <div class="mt-6 h-1 w-full bg-slate-50 rounded-full overflow-hidden">
                    <div class="h-full bg-red-500 w-[30%] group-hover:w-[40%] transition-all duration-1000"></div>
                </div>
            </div>
        </div>

        <!-- Advanced Revenue Analytics -->
        <div class="bg-slate-900 p-12 rounded-[4rem] shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-600/10 rounded-full blur-[120px] opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-primary-600/10 rounded-full blur-[120px] opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>

            <div class="relative z-10 flex flex-col xl:flex-row items-center justify-between gap-16">
                <div class="flex-1 w-full space-y-10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-white font-heading font-black text-2xl tracking-tight">{{ __('admin.revenue_trend') }} <span class="text-white/20 font-sans text-sm font-medium ml-2">{{ __('admin.last_6_months') }}</span></h3>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-600"></div>
                            <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">{{ __('admin.premium_tiers') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-end gap-4 h-64">
                        @php
                            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                        @endphp
                        @foreach($revenueByMonth as $index => $data)
                            @php $height = ($data->total / max($revenueByMonth->max('total'), 1)) * 100; @endphp
                            <div class="flex-1 flex flex-col items-center gap-4 group/bar">
                                <div class="w-full bg-white/5 group-hover/bar:bg-red-600/20 relative rounded-2xl transition-all duration-700 flex items-end justify-center overflow-hidden" 
                                     style="height: 100%">
                                    <div class="w-full bg-gradient-to-t from-red-600 to-red-500 rounded-t-xl transition-all duration-1000 delay-{{ $index * 100 }}"
                                         style="height: {{ max($height, 5) }}%"></div>
                                    <div class="absolute -top-12 left-1/2 -translate-x-1/2 bg-white text-slate-900 px-4 py-2 rounded-xl text-xs font-black shadow-2xl opacity-0 translate-y-2 group-hover/bar:opacity-100 group-hover/bar:translate-y-0 transition-all">
                                        ${{ number_format($data->total, 0) }}
                                    </div>
                                </div>
                                <span class="text-[10px] font-black text-white/20 uppercase tracking-widest">{{ $months[$data->month-1] ?? 'Month' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="w-full xl:w-80 space-y-8">
                    <div class="space-y-2">
                        <p class="text-white/30 text-[10px] font-black uppercase tracking-widest">{{ __('admin.growth_engine') }}</p>
                        <h4 class="text-3xl font-heading font-black text-white tracking-tighter">{{ __('admin.exponential_expansion') }}</h4>
                    </div>

                    <div class="space-y-6">
                        <div class="p-6 bg-white/5 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors">
                            <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-1">{{ __('admin.projected_q4') }}</p>
                            <p class="text-3xl font-black text-white">${{ number_format($projectedRevenue, 2) }}</p>
                        </div>
                        <div class="p-6 bg-red-600 rounded-3xl shadow-xl shadow-red-900/40">
                            <p class="text-white/60 text-[10px] font-black uppercase tracking-widest mb-1">{{ __('admin.growth_rate') }}</p>
                            <p class="text-3xl font-black text-white">+{{ $revenueByMonth->count() > 0 ? number_format(($revenueByMonth->last()->total / max($projectedRevenue, 1)) * 100, 1) : '0' }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Audit Log -->
        <div class="space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-heading font-black text-slate-900 tracking-tight">{{ __('admin.audit_log') }}</h3>
                    <p class="text-slate-400 text-sm font-medium mt-1">{{ __('admin.audit_log_desc') }}</p>
                </div>
                <div class="flex items-center gap-2 bg-slate-100 p-1.5 rounded-2xl">
                    <button class="px-4 py-2 bg-white rounded-xl text-xs font-bold text-slate-900 shadow-sm">{{ __('admin.all_tenants') }}</button>
                    <button class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-900">{{ __('admin.active_only') }}</button>
                </div>
            </div>

            <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="ps-10 pe-6 py-8 text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('admin.tenant_identifier') }}</th>
                                <th class="px-6 py-8 text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('admin.executive_contact') }}</th>
                                <th class="px-6 py-8 text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('admin.tier_architecture') }}</th>
                                <th class="px-6 py-8 text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('admin.financial_yield') }}</th>
                                <th class="px-6 py-8 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right pe-10">{{ __('admin.integration_date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($tenants as $tenant)
                            <tr class="hover:bg-slate-50/30 transition-all group">
                                <td class="ps-10 pe-6 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-[1.25rem] bg-slate-100 flex items-center justify-center font-black text-slate-400 text-lg group-hover:bg-red-600 group-hover:text-white transition-all duration-500 shadow-sm">
                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 tracking-tight">{{ $tenant->name }}</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $tenant->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-8">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-black text-[10px]">
                                            {{ strtoupper(substr($tenant->owner->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-bold text-slate-600">{{ $tenant->owner->name ?? 'System Administrator' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-8">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $tenant->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                                        <span class="px-4 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-[10px] font-black uppercase tracking-widest">
                                            {{ $tenant->plan->name ?? 'Legacy Tier' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-8">
                                    <p class="text-sm font-black text-slate-900 tracking-tight">${{ number_format($tenant->plan->price ?? 0, 2) }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5 tracking-widest">{{ __('admin.monthly_basis') }}</p>
                                </td>
                                <td class="px-6 py-8 text-right pe-10">
                                    <p class="text-sm font-bold text-slate-900 tracking-tight">{{ $tenant->created_at->format('M d, Y') }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5 tracking-widest">{{ $tenant->created_at->diffForHumans() }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-center mt-10">
                {{ $tenants->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
