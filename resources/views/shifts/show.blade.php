@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('shifts.index') }}" class="w-10 h-10 rounded-full flex items-center justify-center bg-white shadow hover:bg-gray-50 text-gray-500 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-3xl font-heading font-black text-gray-900 tracking-tight">{{ __('shifts.title_summary') }}</h2>
                <p class="text-gray-500 mt-1 font-medium italic">{{ __('shifts.shift_number') }}{{ $shift->id }} ({{ $shift->status }})</p>
            </div>
        </div>
        
        <a href="{{ route('shifts.print', $shift) }}" target="_blank" class="px-6 py-2.5 rounded-xl bg-gray-900 text-white text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            {{ __('shifts.print_report') }}
        </a>
    </div>

    {{-- Financial Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">{{ __('shifts.opening_balance') }}</p>
            <p class="text-3xl font-heading font-black text-gray-900">{{ __('messages.currency_symbol') }} {{ number_format($summary['opening_balance'], 2) }}</p>
        </div>
        <div class="bg-gray-900 rounded-[2rem] p-8 shadow-xl text-white">
            <p class="text-[10px] text-white/40 font-black uppercase tracking-widest mb-1">{{ __('shifts.expected_revenue') }}</p>
            <p class="text-3xl font-heading font-black text-primary-400">+{{ __('messages.currency_symbol') }} {{ number_format($summary['total_revenue'], 2) }}</p>
        </div>
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">{{ __('shifts.expenses_logged') }}</p>
            <p class="text-3xl font-heading font-black text-red-500">-{{ __('messages.currency_symbol') }} {{ number_format($summary['expenses_total'], 2) }}</p>
        </div>
    </div>

    {{-- Detailed Breakdown --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 tracking-tight">{{ __('shifts.financial_reconciliation') }}</h3>
            <span class="text-[10px] font-black {{ $summary['difference'] == 0 ? 'text-green-500' : 'text-red-500' }} uppercase tracking-widest px-3 py-1 bg-gray-50 rounded-lg">
                {{ __('shifts.status_label') }} {{ $summary['difference'] == 0 ? __('shifts.balanced') : __('shifts.discrepancy') }}
            </span>
        </div>
        
        <div class="p-8 space-y-4">
            <div class="flex justify-between items-center py-2">
                <span class="text-gray-500 font-medium">{{ __('shifts.session_revenue') }}</span>
                <span class="font-black text-gray-900">{{ __('messages.currency_symbol') }} {{ number_format($summary['session_revenue'], 2) }}</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-gray-500 font-medium">{{ __('shifts.retail_sales') }}</span>
                <span class="font-black text-gray-900">{{ __('messages.currency_symbol') }} {{ number_format($summary['order_revenue'], 2) }}</span>
            </div>
            <hr class="border-gray-50">
            <div class="flex justify-between items-center py-2">
                <span class="text-gray-900 font-black uppercase text-xs tracking-widest">{{ __('shifts.expected_cash') }}</span>
                <span class="text-xl font-heading font-black text-gray-900">{{ __('messages.currency_symbol') }} {{ number_format($summary['expected_cash'], 2) }}</span>
            </div>
            <div class="flex justify-between items-center py-4 bg-primary-50 rounded-2xl px-6">
                <span class="text-primary-800 font-black uppercase text-xs tracking-widest">{{ __('shifts.employee_counted') }}</span>
                <span class="text-2xl font-heading font-black text-primary-600">{{ __('messages.currency_symbol') }} {{ number_format($summary['actual_cash'], 2) }}</span>
            </div>

            @if($summary['difference'] != 0)
            <div class="p-4 rounded-xl {{ $summary['difference'] > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} text-sm font-bold flex items-center gap-3">
                 <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ __('shifts.discrepancy_amount') }} {{ __('messages.currency_symbol') }} {{ number_format($summary['difference'], 2) }} ({{ $summary['difference'] > 0 ? __('shifts.surplus') : __('shifts.deficit') }})
            </div>
            @endif
        </div>
    </div>

    {{-- Audit Log --}}
    <div class="bg-gray-50 rounded-[2rem] p-6 border border-gray-200">
        <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">{{ __('shifts.audit_details') }}</h3>
        <div class="grid grid-cols-2 gap-8">
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-tighter">{{ __('shifts.opened_by') }}</p>
                <p class="text-sm font-bold text-gray-800">{{ $shift->user->name }}</p>
                <p class="text-[10px] text-gray-400">{{ $shift->start_time->format('M d, H:i:s') }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-tighter">{{ __('shifts.closed_at') }}</p>
                <p class="text-sm font-bold text-gray-800">{{ $shift->end_time ? $shift->end_time->format('H:i:s') : __('shifts.still_open') }}</p>
                <p class="text-[10px] text-gray-400">{{ $shift->end_time ? $shift->end_time->diffForHumans($shift->start_time) : '-' }} {{ __('shifts.shift_duration') }}</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .max-w-4xl, .max-w-4xl * { visibility: visible; }
    .max-w-4xl { position: absolute; left: 0; top: 0; width: 100%; }
    button, a { display: none !important; }
}
</style>
@endsection
