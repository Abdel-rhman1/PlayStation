@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('orders.index') }}" class="w-10 h-10 rounded-full flex items-center justify-center bg-white shadow hover:bg-gray-50 text-gray-500 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-3xl font-heading font-black text-gray-900 tracking-tight">Order Details</h2>
                <p class="text-gray-500 mt-1 font-mono text-sm">#{{ $order->id }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            @if($order->status === 'paid' || $order->status === 'completed')
                <div class="px-4 py-2 rounded-xl bg-green-50 text-green-700 text-xs font-black uppercase tracking-widest flex items-center gap-2 border border-green-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Paid
                </div>
            @else
                <div class="px-4 py-2 rounded-xl bg-yellow-50 text-yellow-700 text-xs font-black uppercase tracking-widest border border-yellow-200">
                    {{ $order->status }}
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Order Items --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">Purchased Items</h3>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-primary-500 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $item->product->name ?? 'Unknown Product' }}</p>
                                    <p class="text-xs text-gray-500 font-bold">${{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <p class="text-lg font-heading font-black text-gray-900">${{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="space-y-6">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary-50 rounded-bl-full -z-10"></div>
                
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Summary</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-bold">Subtotal</span>
                        <span class="text-gray-900 font-bold">${{ number_format($order->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-bold">Discount</span>
                        <span class="text-gray-900 font-bold">$0.00</span>
                    </div>
                    
                    <hr class="border-gray-100">
                    
                    <div class="flex justify-between items-end pt-2">
                        <span class="text-gray-900 font-black uppercase text-xs tracking-widest">Total</span>
                        <span class="text-4xl font-heading font-black text-primary-600">${{ number_format($order->total_price, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-[2rem] p-6 border border-gray-200">
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Meta Data</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Date</span>
                        <span class="font-bold text-gray-900">{{ $order->created_at->format('M d, Y H:i A') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Cashier</span>
                        <span class="font-bold text-gray-900">{{ $order->user->name ?? 'System' }}</span>
                    </div>

                    @if($order->session)
                    <hr class="border-gray-200">
                    <div class="pt-1">
                        <p class="text-[10px] font-black text-primary-600 uppercase tracking-[0.2em] mb-2">Linked Session</p>
                        <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-black text-gray-900 truncate">{{ $order->session->device->name ?? 'Unknown Device' }}</p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Started: {{ $order->session->started_at->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <button onclick="window.print()" class="w-full bg-gray-900 text-white font-black py-4 rounded-2xl shadow hover:bg-black transition-all uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Receipt
            </button>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .max-w-4xl, .max-w-4xl * { visibility: visible; }
    .max-w-4xl { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none !important; }
    button { display: none !important; }
}
</style>
@endsection
