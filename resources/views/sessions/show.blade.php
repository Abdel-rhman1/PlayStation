@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-in fade-in duration-500" x-data="liveSession({{ json_encode([
    'started_at' => $session->started_at->toIso8601String(),
    'hourly_rate' => (float) $session->device->hourly_rate,
    'fixed_rate' => (float) $session->device->fixed_rate,
    'pricing_type' => $session->pricing_type,
    'unpaid_orders_total' => (float) $session->orders()->unpaid()->sum('total_price'),
    'paid_orders_total' => (float) $session->orders()->paid()->sum('total_price'),
]) }})">
    
    <!-- Header: Dynamic Status & Timer -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-3xl bg-primary-600 flex items-center justify-center text-white shadow-xl shadow-primary-200">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h2 class="text-3xl font-heading font-black text-gray-900 tracking-tight">{{ $session->device->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">{{ __('dashboard.active') }} • {{ $session->started_at->format('H:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <div class="bg-white px-6 py-4 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="text-start">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">{{ __('sessions.duration') }}</p>
                    <p class="text-2xl font-mono font-black text-primary-600" x-text="timer"></p>
                </div>
                <div class="w-px h-8 bg-gray-100"></div>
                <div class="text-start">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">{{ __('devices.hourly_rate') }}</p>
                    <p class="text-lg font-black text-gray-900">{{ __('messages.currency_symbol') }} {{ number_format($session->device->hourly_rate, 2) }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('pos.index', ['device_id' => $session->device_id]) }}" class="bg-gray-900 text-white px-6 py-4 rounded-2xl text-sm font-bold shadow-lg hover:bg-gray-800 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    {{ __('pos.new_order') }}
                </a>
                <form action="{{ route('sessions.stop', $session->device) }}" method="POST" onsubmit="return confirm('{{ __('dashboard.stop_confirm') }}')">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-6 py-4 rounded-2xl text-sm font-bold shadow-lg shadow-red-100 hover:bg-red-700 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ __('dashboard.stop_session') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Orders List -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between px-2">
                <h3 class="text-xl font-heading font-black text-gray-900">{{ __('orders.title') }}</h3>
                <span class="bg-gray-100 text-gray-600 text-[10px] font-black px-3 py-1 rounded-full uppercase">{{ $session->orders->count() }} {{ __('orders.items') }}</span>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-start">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-50">
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('orders.items') }}</th>
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">{{ __('orders.total_amount') }}</th>
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('orders.status') }}</th>
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @forelse($session->orders as $order)
                        <tr class="hover:bg-gray-50/30 transition-colors" x-data="{ status: '{{ $order->payment_status }}', paying: false }">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-900">
                                        {{ $order->items->map(fn($i) => $i->quantity . 'x ' . $i->product->name)->join(', ') }}
                                    </span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $order->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-end font-black text-gray-900">
                                {{ __('messages.currency_symbol') }} {{ number_format($order->total_price, 2) }}
                            </td>
                            <td class="px-8 py-6">
                                <template x-if="status === 'paid'">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-50 text-green-700 text-[10px] font-black uppercase tracking-widest">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        {{ __('orders.paid') }}
                                    </span>
                                </template>
                                <template x-if="status !== 'paid'">
                                    <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-[10px] font-black uppercase tracking-widest">{{ __('orders.unpaid') }}</span>
                                </template>
                            </td>
                            <td class="px-8 py-6 text-end">
                                <template x-if="status !== 'paid'">
                                    <button @click="payOrder('{{ $order->id }}', {{ $order->total_price }}, $data)" 
                                            :disabled="paying"
                                            class="text-primary-600 font-black text-xs hover:underline uppercase tracking-widest disabled:opacity-50">
                                        <span x-show="!paying">{{ __('orders.pay_now') }}</span>
                                        <span x-show="paying" class="flex items-center gap-1">
                                            <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            ...
                                        </span>
                                    </button>
                                </template>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-gray-400 italic">
                                {{ __('orders.no_orders') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totals & Bill Summary -->
        <div class="space-y-6">
            <h3 class="text-xl font-heading font-black text-gray-900 px-2">{{ __('dashboard.billing_summary') }}</h3>
            
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 space-y-8 relative overflow-hidden">
                <div class="space-y-6 relative z-10">
                    <!-- Device Cost -->
                    <div class="flex items-center justify-between group">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('devices.usage_cost') }}</span>
                            <span class="text-sm font-bold text-gray-900 group-hover:text-primary-600 transition-colors">{{ __('dashboard.running_time') }}</span>
                        </div>
                        <span class="text-xl font-black text-gray-900" x-text="'{{ __('messages.currency_symbol') }} ' + runningCost.toFixed(2)"></span>
                    </div>

                    <div class="h-px w-full bg-gray-50"></div>

                    <!-- Paid Orders -->
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('orders.paid_total') }}</span>
                            <span class="text-sm font-bold text-green-600">{{ __('orders.settled_items') }}</span>
                        </div>
                        <span class="text-xl font-black text-green-600" x-text="'{{ __('messages.currency_symbol') }} ' + paidTotal.toFixed(2)"></span>
                    </div>

                    <!-- Unpaid Orders -->
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('orders.unpaid_total') }}</span>
                            <span class="text-sm font-bold text-red-600">{{ __('orders.pending_items') }}</span>
                        </div>
                        <span class="text-xl font-black text-red-600" x-text="'{{ __('messages.currency_symbol') }} ' + unpaidTotal.toFixed(2)"></span>
                    </div>

                    <div class="pt-6 border-t-2 border-dashed border-gray-100">
                        <div class="flex items-center justify-between bg-primary-50/50 p-6 rounded-3xl border border-primary-100">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black text-primary-600 uppercase tracking-widest">{{ __('dashboard.due_now') }}</span>
                                <span class="text-xs font-bold text-primary-400">{{ __('dashboard.usage_and_pending') }}</span>
                            </div>
                            <span class="text-3xl font-black text-primary-600 tracking-tighter" x-text="'{{ __('messages.currency_symbol') }} ' + (runningCost + unpaidTotal).toFixed(2)"></span>
                        </div>
                    </div>
                </div>

                <!-- Abstract background elements -->
                <div class="absolute -end-10 -top-10 w-40 h-40 bg-primary-50/30 rounded-full blur-3xl"></div>
                <div class="absolute -start-10 -bottom-10 w-40 h-40 bg-blue-50/30 rounded-full blur-3xl"></div>
            </div>

            <div class="bg-amber-50 border border-amber-100 rounded-3xl p-6 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-amber-900 uppercase tracking-tighter">{{ __('dashboard.operator_note') }}</p>
                    <p class="text-[11px] text-amber-700 font-medium mt-1 leading-relaxed">{{ __('dashboard.session_live_warning') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function liveSession(data) {
        return {
            startedAt: new Date(data.started_at),
            hourlyRate: data.hourly_rate,
            fixedRate: data.fixed_rate,
            pricingType: data.pricing_type,
            unpaidTotal: data.unpaid_orders_total,
            paidTotal: data.paid_orders_total,
            timer: '00:00:00',
            runningCost: 0,

            init() {
                this.update();
                setInterval(() => this.update(), 1000);

                // Re-sync data when page becomes visible or returned from cache
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'visible') {
                        this.refreshData();
                    }
                });

                window.addEventListener('pageshow', (event) => {
                    if (event.persisted || performance.getEntriesByType(\"navigation\")[0].type === 'back_forward') {
                        this.refreshData();
                    }
                });
            },

            async refreshData() {
                try {
                    const response = await fetch(`{{ route('sessions.data', $session) }}`);
                    const data = await response.json();
                    
                    if (data.status !== 'active') {
                        window.location.reload(); // If session ended elsewhere, refresh to show receipt
                        return;
                    }

                    this.startedAt = new Date(data.started_at);
                    this.unpaidTotal = data.unpaid_orders_total;
                    this.paidTotal = data.paid_orders_total;
                    this.update();
                } catch (e) {
                    console.error('Failed to sync session data');
                }
            },

            update() {
                const now = new Date();
                const diff = Math.floor((now - this.startedAt) / 1000);
                
                const h = Math.floor(diff / 3600);
                const m = Math.floor((diff % 3600) / 60);
                const s = diff % 60;
                
                this.timer = [h, m, s].map(v => v.toString().padStart(2, '0')).join(':');

                // Calculate cost
                const hours = diff / 3600;
                this.runningCost = this.fixedRate + (hours * this.hourlyRate);
            },

            async payOrder(orderId, price, row) {
                if (row.paying) return;
                row.paying = true;

                try {
                    const response = await fetch(`/orders/${orderId}/pay`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        row.status = 'paid';
                        this.unpaidTotal -= price;
                        this.paidTotal += price;
                        this.addToast(result.message);
                    } else {
                        this.addToast(result.message, 'error');
                    }
                } catch (e) {
                    this.addToast('Failed to process payment.', 'error');
                } finally {
                    row.paying = false;
                }
            }
        }
    }
</script>
@endsection
