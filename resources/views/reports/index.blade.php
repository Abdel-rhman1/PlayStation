@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('reports.title') }}</h2>
            <p class="text-gray-500 text-sm">{{ __('reports.subtitle') }}</p>
        </div>
        
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2 bg-white p-2 rounded-2xl border border-gray-100 shadow-sm">
            <input type="date" name="from" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}" class="border-none bg-transparent text-xs font-bold text-gray-600 focus:ring-0">
            <span class="text-gray-300">-</span>
            <input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}" class="border-none bg-transparent text-xs font-bold text-gray-600 focus:ring-0">
            <button type="submit" class="p-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </button>
        </form>
    </div>

    <!-- Big Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm group hover:border-primary-500 transition-all duration-300">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">{{ __('reports.revenue') }}</p>
            <p class="text-4xl font-heading font-black text-gray-900 tracking-tight">${{ number_format($totalRevenue ?? 0, 2) }}</p>
            <div class="mt-4 flex items-center gap-2 text-primary-600 text-[10px] font-black uppercase tracking-widest bg-primary-50 w-fit px-3 py-1 rounded-full">
                {{ __('reports.revenue_source') }}
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm group hover:border-red-500 transition-all duration-300">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">{{ __('reports.operational_costs') }}</p>
            <p class="text-4xl font-heading font-black text-gray-900 tracking-tight">${{ number_format($totalExpenses ?? 0, 2) }}</p>
            <div class="mt-4 flex items-center gap-2 text-red-600 text-[10px] font-black uppercase tracking-widest bg-red-50 w-fit px-3 py-1 rounded-full">
                {{ __('reports.expense_source') }}
            </div>
        </div>

        <div class="bg-primary-600 p-8 rounded-[2rem] text-white shadow-2xl shadow-primary-100 relative overflow-hidden">
            <div class="absolute -end-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-primary-100 uppercase tracking-[0.2em] mb-1">{{ __('reports.net_profit') }}</p>
                <p class="text-4xl font-heading font-black tracking-tight">${{ number_format($netProfit ?? 0, 2) }}</p>
                <div class="mt-4 flex items-center gap-2 text-white/80 text-[10px] font-black uppercase tracking-widest bg-black/20 w-fit px-3 py-1 rounded-full">
                    {{ __('reports.profit_source') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Area -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Revenue Curve -->
        <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm space-y-6">
            <h3 class="text-xl font-heading font-black text-gray-900">{{ __('reports.revenue_growth') }}</h3>
            <div class="h-[350px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Expense Composition -->
        <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm space-y-6">
            <h3 class="text-xl font-heading font-black text-gray-900">{{ __('reports.expense_allocation') }}</h3>
            <div class="h-[350px] flex justify-center">
                <canvas id="expenseAllocationChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Line Chart for Revenue
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueChartData['labels'] ?? []) !!},
                datasets: [{
                    label: "{{ __('reports.chart_revenue') }} ($)",
                    data: {!! json_encode($revenueChartData['values'] ?? []) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.05)',
                    borderWidth: 5,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f3f4f6', borderDash: [5, 5] },
                        ticks: { font: { weight: 'bold' } }
                    },
                    x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
                }
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
                    backgroundColor: ['#2563eb', '#3b82f6', '#60a5fa', '#93c5fd', '#dbeafe'],
                    hoverOffset: 20,
                    borderWidth: 0,
                    spacing: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 30,
                            font: { weight: 'black', size: 11 }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
