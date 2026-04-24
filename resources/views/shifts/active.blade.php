@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-10 py-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-heading font-black text-gray-900 tracking-tight">{{ __('shifts.title_active') }}</h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="w-2h h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                <p class="text-gray-500 font-medium italic">{{ __('shifts.tracking_in_progress') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        {{-- Left: Current Status --}}
        <div class="bg-white rounded-[3rem] p-10 border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary-50 rounded-bl-full -z-10"></div>
            
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-8">{{ __('shifts.work_session_info') }}</h3>
            
            <div class="space-y-8">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-primary-600 flex items-center justify-center text-white shadow-lg shadow-primary-100">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">{{ __('shifts.started_at') }}</p>
                        <p class="text-xl font-black text-gray-900">{{ $currentShift->start_time->format('H:i A') }}</p>
                        <p class="text-xs text-primary-600 font-bold uppercase tracking-tight">{{ $currentShift->start_time->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">{{ __('shifts.opening_drawer_cash') }}</p>
                        <p class="text-xl font-black text-gray-900">${{ number_format($currentShift->opening_balance, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Closing Form --}}
        <div class="bg-gray-900 rounded-[3rem] p-10 shadow-2xl text-white relative">
            <h3 class="text-xs font-black text-white/30 uppercase tracking-widest mb-8">{{ __('shifts.close_shift') }}</h3>
            
            <form action="{{ route('shifts.close') }}" method="POST" class="space-y-8">
                @csrf
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-white/50 uppercase tracking-widest ms-2">{{ __('shifts.final_cash') }}</label>
                    <div class="relative group">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-white/20 font-bold transition-colors group-focus-within:text-primary-400">$</span>
                        <input type="number" step="0.01" name="closing_balance" required
                               class="w-full bg-white/5 border-2 border-transparent focus:border-primary-500/30 focus:bg-white/10 focus:ring-0 rounded-[2rem] ps-16 pe-8 py-5 font-black text-2xl text-white placeholder:text-white/5"
                               placeholder="0.00">
                    </div>
                </div>

                <div class="p-6 rounded-2xl bg-white/5 border border-white/5 space-y-2">
                    <div class="flex items-center gap-3 text-white/40">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-[10px] font-bold uppercase tracking-widest">{{ __('shifts.end_of_day_process') }}</p>
                    </div>
                    <p class="text-xs text-white/60 leading-relaxed font-medium">{{ __('shifts.count_instructions') }}</p>
                </div>

                <button type="submit" class="w-full bg-primary-600 text-white font-black py-5 rounded-[2rem] shadow-xl shadow-black/20 hover:bg-primary-700 active:scale-95 transition-all uppercase tracking-[0.2em] text-xs">
                    {{ __('shifts.btn_finalize') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
