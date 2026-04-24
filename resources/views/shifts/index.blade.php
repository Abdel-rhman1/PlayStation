@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-heading font-black text-gray-900 tracking-tight">Shift Management</h2>
            <p class="text-gray-500 mt-1">Manage your work session and cash balance.</p>
        </div>
        @if($currentShift)
            <div class="px-4 py-2 rounded-xl bg-green-50 text-green-700 text-xs font-black uppercase tracking-widest flex items-center gap-2 border border-green-200">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Shift Active
            </div>
        @else
            <div class="px-4 py-2 rounded-xl bg-gray-50 text-gray-400 text-xs font-black uppercase tracking-widest border border-gray-200">
                Shift Closed
            </div>
        @endif
    </div>

    @if(!$currentShift)
        {{-- Start Shift Card --}}
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary-50 rounded-bl-full -z-10 opacity-50"></div>
            
            <div class="max-w-md">
                <h3 class="text-2xl font-black text-gray-900 mb-2">Ready to start?</h3>
                <p class="text-gray-500 mb-8 font-medium">Please enter your opening cash balance to start your work shift.</p>

                <form action="{{ route('shifts.start') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ms-1">Opening Balance</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 font-bold">$</span>
                            <input type="number" step="0.01" name="opening_balance" value="0.00" required
                                   class="w-full bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary-50 rounded-2xl ps-10 pe-6 py-4 font-black text-gray-900">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-primary-100 hover:bg-primary-700 active:scale-95 transition-all flex items-center justify-center gap-3">
                        <span>Open Shift</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </form>
            </div>
        </div>
    @else
        {{-- Close Shift Card --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Active Shift Info</h3>
                
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-primary-50 flex items-center justify-center text-primary-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Started At</p>
                            <p class="font-black text-gray-900">{{ $currentShift->start_time->format('H:i A') }}</p>
                            <p class="text-xs text-gray-500 font-medium">{{ $currentShift->start_time->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Opening Balance</p>
                            <p class="font-black text-gray-900">${{ number_format($currentShift->opening_balance, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-[2.5rem] p-8 shadow-xl text-white">
                <h3 class="text-xs font-black text-white/40 uppercase tracking-widest mb-6">Close Your Shift</h3>
                
                <form action="{{ route('shifts.close') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-white/50 uppercase tracking-widest ms-1">Closing Cash Balance</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-white/30 font-bold">$</span>
                            <input type="number" step="0.01" name="closing_balance" required
                                   class="w-full bg-white/5 border-transparent focus:bg-white/10 focus:ring-4 focus:ring-primary-500/20 rounded-2xl ps-10 pe-6 py-4 font-black text-white placeholder:text-white/10"
                                   placeholder="Enter counted cash...">
                        </div>
                    </div>

                    <p class="text-[10px] text-white/30 italic">Entering the closing balance will finalize all transactions for this shift and mark it as completed.</p>

                    <button type="submit" class="w-full bg-white text-gray-900 font-black py-4 rounded-2xl hover:bg-gray-100 active:scale-95 transition-all uppercase tracking-widest text-xs">
                        Finalize & Close Shift
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
