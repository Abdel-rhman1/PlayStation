@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('orders.title') }}</h2>
            <p class="text-gray-500 text-sm">{{ __('orders.subtitle') }}</p>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('orders.export') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition-all">
                {{ __('orders.export_csv') }}
            </a>
            <a href="{{ route('pos.index') }}" class="bg-primary-600 text-white px-6 py-3 rounded-2xl text-sm font-bold shadow-lg hover:bg-primary-700 transition-all">
                {{ __('orders.new_order') }}
            </a>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('orders.order_id') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('orders.cashier') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('orders.items') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">{{ __('orders.total_amount') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('orders.status') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">{{ __('orders.date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors group cursor-pointer" onclick="window.location='{{ route('orders.show', $order) }}'">
                        <td class="px-8 py-6">
                            <span class="font-mono text-xs font-bold text-gray-400">#{{ substr($order->id, 0, 8) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs">
                                    {{ substr($order->user->name ?? '?', 0, 1) }}
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ $order->user->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm text-gray-600 font-medium">{{ __('orders.item_count', ['count' => $order->items->count()]) }}</span>
                        </td>
                        <td class="px-8 py-6 text-end font-heading font-black text-gray-900">
                            {{ __('messages.currency_symbol') }} {{ number_format($order->total_price, 2) }}
                        </td>
                        <td class="px-8 py-6">
                            @if($order->status === 'paid' || $order->status === 'completed')
                                <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-[10px] font-black uppercase tracking-widest">{{ __('orders.paid') }}</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 text-[10px] font-black uppercase tracking-widest">{{ $order->status }}</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-end text-xs font-bold text-gray-400">
                            {{ $order->created_at->format('M d, H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center italic text-gray-400 bg-white">
                            {{ __('orders.no_orders') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
