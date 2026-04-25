<x-app-layout>
    <div class="space-y-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-4xl font-heading font-black tracking-tighter text-gray-900">{{ __('admin.system_report') }}</h1>
                <p class="text-gray-400 font-medium mt-2">{{ __('admin.platform_audit') }}</p>
            </div>
            <div class="flex gap-4">
                <button onclick="window.print()" class="px-6 py-3 bg-gray-900 rounded-2xl shadow-lg shadow-gray-200 font-bold text-white hover:bg-black transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    {{ __('admin.print_report') }}
                </button>
            </div>
        </div>

        <!-- Revenue Chart Simulation -->
        <div class="bg-gray-900 p-10 rounded-[3rem] shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/10 blur-[100px]"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-end justify-between gap-10">
                <div class="flex-1 space-y-6">
                    <h3 class="text-white/40 text-xs font-black uppercase tracking-[0.2em]">{{ __('admin.revenue_trend') }}</h3>
                    <div class="flex items-end gap-2 h-48">
                        @foreach($revenueByMonth as $data)
                            @php $height = ($data->total / 1000) * 100; @endphp
                            <div class="flex-1 bg-primary-500/20 group relative rounded-t-xl hover:bg-primary-500 transition-all duration-500" style="height: {{ max($height, 10) }}%">
                                <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-white text-gray-900 px-3 py-1 rounded-lg text-[10px] font-black opacity-0 group-hover:opacity-100 transition-opacity">
                                    ${{ number_format($data->total, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between text-[10px] font-black text-white/20 uppercase tracking-widest pt-4 border-t border-white/5">
                        <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
                    </div>
                </div>
                <div class="w-full md:w-64 space-y-6">
                    <div class="bg-white/5 p-6 rounded-3xl border border-white/10">
                        <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-1">{{ __('admin.projected_q4') }}</p>
                        <p class="text-2xl font-black text-white">${{ number_format($projectedRevenue, 2) }}</p>
                    </div>
                    <div class="bg-primary-500/10 p-6 rounded-3xl border border-primary-500/20">
                        <p class="text-primary-400 text-[10px] font-black uppercase tracking-widest mb-1">{{ __('admin.growth_rate') }}</p>
                        <p class="text-2xl font-black text-primary-500">+{{ $revenueByMonth->count() > 0 ? number_format(($revenueByMonth->last()->total / $projectedRevenue) * 100, 1) : '0' }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tenant Audit Log -->
        <div class="space-y-6">
            <h3 class="text-xl font-black text-gray-900 tracking-tight">{{ __('admin.audit_log') }}</h3>
            <div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-black/5 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-50 bg-gray-50/30">
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.tenant_details') }}</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.tenant_owner') }}</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.plan_billing') }}</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Revenue</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">{{ __('admin.join_date') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm font-bold text-gray-600">
                        @foreach($tenants as $tenant)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-6 text-gray-900 tracking-tight">{{ $tenant->name }}</td>
                            <td class="px-8 py-6">{{ $tenant->owner->name ?? 'System' }}</td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full bg-primary-50 text-primary-600 text-[10px] font-black uppercase tracking-widest">
                                    {{ $tenant->plan->name ?? 'Trial' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-gray-900">${{ number_format($tenant->plan->price ?? 0, 2) }}</td>
                            <td class="px-8 py-6 text-right text-gray-400">{{ $tenant->created_at->toFormattedDateString() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $tenants->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
