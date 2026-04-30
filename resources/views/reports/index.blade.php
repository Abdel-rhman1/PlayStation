@extends('layouts.app')

@section('content')
<div class="space-y-10 py-6 font-sans">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Page Header & Filter -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 relative z-10">
        <div>
            <h2 class="text-4xl font-heading font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-500 tracking-tight">{{ __('reports.title') }}</h2>
            <p class="text-gray-500 text-sm font-medium mt-1">{{ __('reports.subtitle') }}</p>
        </div>
        
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-3 bg-white p-2.5 rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/40 backdrop-blur-xl transition-all hover:shadow-2xl hover:shadow-primary-500/10">
            <div class="flex items-center px-4 bg-gray-50 rounded-[1.5rem] border border-gray-100 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4 text-gray-400 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <input type="date" name="from" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}" class="border-none bg-transparent text-xs font-black text-gray-700 focus:ring-0 py-3 pe-0 w-[110px]">
            </div>
            <span class="text-gray-300 font-bold px-1">-</span>
            <div class="flex items-center px-4 bg-gray-50 rounded-[1.5rem] border border-gray-100 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4 text-gray-400 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}" class="border-none bg-transparent text-xs font-black text-gray-700 focus:ring-0 py-3 pe-0 w-[110px]">
            </div>
            <button type="submit" class="w-12 h-12 flex items-center justify-center bg-primary-600 text-white rounded-[1.5rem] hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30 hover:scale-105 active:scale-95">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>

    <!-- Big Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Revenue Card -->
        <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-200/30 group hover:border-emerald-500/20 transition-all duration-500 relative overflow-hidden flex flex-col justify-between hover:-translate-y-2">
            <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-bl from-emerald-100/50 to-transparent rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-700"></div>
            
            <div>
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 border border-emerald-100/50 shadow-inner group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-500 text-emerald-500">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">{{ __('reports.revenue') }}</p>
                <p class="text-5xl font-heading font-black text-gray-900 tracking-tighter">{{ __('messages.currency_symbol') }} {{ number_format($totalRevenue ?? 0, 2) }}</p>
            </div>
            <div class="mt-8 flex items-center gap-2 text-emerald-600 text-[10px] font-black uppercase tracking-widest bg-emerald-50 w-fit px-3 py-1.5 rounded-full border border-emerald-100">
                {{ __('reports.revenue_source') }}
            </div>
        </div>

        <!-- Expense Card -->
        <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-200/30 group hover:border-rose-500/20 transition-all duration-500 relative overflow-hidden flex flex-col justify-between hover:-translate-y-2">
            <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-bl from-rose-100/50 to-transparent rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-700"></div>
            
            <div>
                <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center mb-6 border border-rose-100/50 shadow-inner group-hover:bg-rose-500 group-hover:text-white transition-colors duration-500 text-rose-500">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">{{ __('reports.operational_costs') }}</p>
                <p class="text-5xl font-heading font-black text-gray-900 tracking-tighter">{{ __('messages.currency_symbol') }} {{ number_format($totalExpenses ?? 0, 2) }}</p>
            </div>
            <div class="mt-8 flex items-center gap-2 text-rose-600 text-[10px] font-black uppercase tracking-widest bg-rose-50 w-fit px-3 py-1.5 rounded-full border border-rose-100">
                {{ __('reports.expense_source') }}
            </div>
        </div>

        <!-- Net Profit Card (Premium) -->
        <div class="bg-gray-900 p-10 rounded-[2.5rem] text-white shadow-2xl shadow-gray-900/40 relative overflow-hidden flex flex-col justify-between group hover:-translate-y-2 transition-all duration-500">
            <!-- Glassmorphism Elements -->
            <div class="absolute -end-10 -top-10 w-40 h-40 bg-gradient-to-br from-primary-400 to-indigo-500 rounded-full blur-2xl opacity-40 group-hover:scale-150 group-hover:opacity-60 transition-all duration-700"></div>
            <div class="absolute -start-10 -bottom-10 w-40 h-40 bg-gradient-to-tr from-rose-400 to-orange-400 rounded-full blur-3xl opacity-20"></div>
            
            <div class="relative z-10">
                <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/10 group-hover:bg-white group-hover:text-gray-900 transition-colors duration-500 text-white">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                </div>
                <p class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em] mb-1">{{ __('reports.net_profit') }}</p>
                <p class="text-5xl font-heading font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">{{ __('messages.currency_symbol') }} {{ number_format($netProfit ?? 0, 2) }}</p>
            </div>
            <div class="mt-8 relative z-10 flex items-center gap-2 text-white/90 text-[10px] font-black uppercase tracking-widest bg-white/10 backdrop-blur-sm shadow-inner w-fit px-4 py-2 rounded-full border border-white/5 group-hover:bg-white/20 transition-colors">
                {{ __('reports.profit_source') }}
            </div>
        </div>
    </div>

    <!-- Charts Area -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Revenue Curve -->
        <div class="lg:col-span-3 bg-white p-8 md:p-10 rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-200/30">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('reports.revenue_growth') }}</h3>
                <div class="p-2 bg-gray-50 rounded-xl">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
            </div>
            <div class="h-[400px] w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Expense Composition -->
        <div class="lg:col-span-2 bg-white p-8 md:p-10 rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-200/30 flex flex-col">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('reports.expense_allocation') }}</h3>
                <div class="p-2 bg-gray-50 rounded-xl">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                </div>
            </div>
            <div class="h-[350px] w-full flex-1 flex items-center justify-center relative">
                <canvas id="expenseAllocationChart"></canvas>
                <!-- Center Hole Decorator -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-32 h-32 rounded-full border-[12px] border-white shadow-inner bg-gray-50 flex items-center justify-center flex-col">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ __('reports.operational_costs') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaderboards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Devices -->
        <div class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-200/30">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('reports.top_devices') }}</h3>
                <div class="p-2 bg-gray-50 rounded-xl">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                </div>
            </div>
            @forelse($topDevices as $index => $device)
            @php $colors = ['primary','indigo','purple','pink','rose']; $color = $colors[$index % count($colors)]; @endphp
            <div class="flex items-center gap-4 py-4 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                <div class="w-8 h-8 rounded-xl bg-{{ $color }}-100 text-{{ $color }}-600 flex items-center justify-center text-xs font-black">
                    {{ $index + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-900 truncate text-sm">{{ $device->name }}</p>
                    <div class="mt-1.5 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                        @php $maxRev = $topDevices->max('total_revenue') ?: 1; $pct = round(($device->total_revenue / $maxRev) * 100); @endphp
                        <div class="h-full bg-{{ $color }}-500 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                <span class="text-sm font-black text-gray-900 shrink-0">{{ __('messages.currency_symbol') }} {{ number_format($device->total_revenue, 2) }}</span>
            </div>
            @empty
            <div class="py-12 text-center flex flex-col items-center justify-center space-y-4">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-200">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7" /></svg>
                </div>
                <p class="text-gray-400 text-sm font-medium">No device data yet for this period.</p>
                <a href="{{ route('devices.index') }}" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all">
                    {{ __('devices.add_device') }}
                </a>
            </div>
            @endforelse
        </div>

        <!-- Top Products -->
        <div class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-200/30">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('pos.products') }}</h3>
                <div class="p-2 bg-gray-50 rounded-xl">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
            </div>
            @forelse($topProducts as $index => $product)
            @php $colors = ['emerald','teal','cyan','sky','blue']; $color = $colors[$index % count($colors)]; @endphp
            <div class="flex items-center gap-4 py-4 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                <div class="w-8 h-8 rounded-xl bg-{{ $color }}-100 text-{{ $color }}-600 flex items-center justify-center text-xs font-black">
                    {{ $index + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-900 truncate text-sm">{{ $product->name }}</p>
                    <div class="mt-1.5 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                        @php $maxSold = $topProducts->max('total_sold') ?: 1; $pct = round(($product->total_sold / $maxSold) * 100); @endphp
                        <div class="h-full bg-{{ $color }}-500 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                <span class="text-sm font-black text-gray-900 shrink-0">{{ $product->total_sold }} {{ __('pos.products') }}</span>
            </div>
            @empty
            <div class="py-12 text-center flex flex-col items-center justify-center space-y-4">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-200">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <p class="text-gray-400 text-sm font-medium">No product sales yet for this period.</p>
                <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all">
                    {{ __('pos.inventory') ?? 'Manage Inventory' }}
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#9ca3af';

        // Line Chart for Revenue
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        
        // Setup vibrant blue gradient
        let gradientFill = ctxRev.createLinearGradient(0, 0, 0, 400);
        gradientFill.addColorStop(0, 'rgba(56, 189, 248, 0.4)'); // text-sky-400
        gradientFill.addColorStop(1, 'rgba(56, 189, 248, 0.0)');

        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueChartData['labels'] ?? []) !!},
                datasets: [{
                    label: "{{ __('reports.chart_revenue') }} ({{ __('messages.currency_symbol') }})",
                    data: {!! json_encode($revenueChartData['values'] ?? []) !!},
                    borderColor: '#0ea5e9', // text-sky-500
                    backgroundColor: gradientFill,
                    borderWidth: 4,
                    tension: 0.5,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#0ea5e9',
                    pointBorderWidth: 4,
                    pointRadius: 6,
                    pointHoverRadius: 10,
                    pointHoverBorderWidth: 4,
                    pointHoverBackgroundColor: '#0ea5e9',
                    pointHoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        padding: 16,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 14, weight: 'bold' },
                        displayColors: false,
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                return '{{ __('messages.currency_symbol') }}' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f3f4f6', strokeDash: [6, 6], drawBorder: false },
                        ticks: { font: { weight: 'bold' }, padding: 10, callback: (val) => '{{ __('messages.currency_symbol') }}' + val }
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { font: { weight: 'bold' }, padding: 10 } 
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
            }
        });

        // Doughnut Chart for Expenses
        const ctxExp = document.getElementById('expenseAllocationChart').getContext('2d');
        new Chart(ctxExp, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($expenseChartData['labels'] ?? []) !!},
                datasets: [{
                    data: {!! json_encode($expenseChartData['values'] ?? []) !!},
                    backgroundColor: [
                        '#6366f1', // Indigo
                        '#ec4899', // Pink
                        '#f59e0b', // Amber
                        '#10b981', // Emerald
                        '#8b5cf6', // Violet
                        '#64748b'  // Slate
                    ],
                    hoverOffset: 12,
                    borderWidth: 6,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 25,
                            font: { weight: 'bold', size: 12, family: "'Inter', sans-serif" },
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        padding: 16,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 14, weight: 'bold' },
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                return ' $' + context.parsed.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
