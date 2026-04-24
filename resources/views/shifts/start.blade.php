@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-12">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-heading font-black text-gray-900 tracking-tight mb-2">{{ __('shifts.title_open') }}</h2>
        <p class="text-gray-500 font-medium">{{ __('shifts.subtitle_open') }}</p>
    </div>

    <div class="bg-white rounded-[3rem] p-12 shadow-xl shadow-gray-100 border border-gray-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-primary-50 rounded-bl-full -z-10 opacity-60"></div>
        
        <form action="{{ route('shifts.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="space-y-3">
                <label class="text-xs font-black text-gray-400 uppercase tracking-widest ms-2">{{ __('shifts.initial_cash') }}</label>
                <div class="relative group">
                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 transition-colors group-focus-within:text-primary-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    <input type="number" step="0.01" name="opening_balance" value="0.00" required autofocus
                           class="w-full bg-gray-50 border-2 border-transparent focus:border-primary-100 focus:bg-white focus:ring-4 focus:ring-primary-50 rounded-[2rem] ps-16 pe-8 py-5 font-black text-2xl text-gray-900 transition-all">
                </div>
            </div>

            <button type="submit" class="w-full bg-primary-600 text-white font-black py-6 rounded-[2rem] shadow-2xl shadow-primary-200 hover:bg-primary-700 hover:-translate-y-1 active:scale-95 transition-all text-lg flex items-center justify-center gap-4">
                <span>{{ __('shifts.btn_start') }}</span>
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
            </button>
        </form>
    </div>
</div>
@endsection
