@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('devices.index') }}" class="p-2 bg-white rounded-xl border border-gray-100 text-gray-400 hover:text-gray-900 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('devices.add_device') }}</h2>
            <p class="text-gray-500 text-sm">{{ __('devices.subtitle') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('devices.store') }}" method="POST" class="p-10 space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Device Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           class="w-full bg-gray-50 border-transparent rounded-[1.5rem] px-6 py-4 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all font-bold text-gray-900 @error('name') ring-2 ring-red-500 @enderror" 
                           placeholder="e.g. PlayStation 5 - Pro Room">
                    @error('name') <p class="mt-2 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Branch Location</label>
                    <select name="branch_id" required 
                            class="w-full bg-gray-50 border-transparent rounded-[1.5rem] px-6 py-4 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all font-bold text-gray-900">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">IP Address (Optional)</label>
                    <input type="text" name="ip_address" value="{{ old('ip_address') }}" 
                           class="w-full bg-gray-50 border-transparent rounded-[1.5rem] px-6 py-4 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all font-bold text-gray-900" 
                           placeholder="192.168.1.50">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Hourly Rate ({{ __('messages.currency_symbol') }})</label>
                    <div class="relative">
                        <span class="absolute start-6 top-1/2 -translate-y-1/2 font-black text-gray-400">$</span>
                        <input type="number" step="0.01" name="hourly_rate" value="{{ old('hourly_rate', '10.00') }}" required 
                               class="w-full bg-gray-50 border-transparent rounded-[1.5rem] ps-10 pe-6 py-4 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all font-black text-gray-900">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Fixed Start Rate ({{ __('messages.currency_symbol') }})</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-gray-400">$</span>
                        <input type="number" step="0.01" name="fixed_rate" value="{{ old('fixed_rate', '0.00') }}" 
                               class="w-full bg-gray-50 border-transparent rounded-[1.5rem] ps-10 pe-6 py-4 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all font-black text-gray-900">
                    </div>
                </div>
            </div>

            <input type="hidden" name="status" value="OFF">

            <div class="pt-6 border-t border-gray-50 flex gap-4">
                <a href="{{ route('devices.index') }}" class="flex-1 py-5 rounded-2xl bg-gray-100 font-bold text-gray-500 text-center hover:bg-gray-200 transition-all">Cancel</a>
                <button type="submit" class="flex-1 py-5 rounded-2xl bg-gray-900 font-black text-white shadow-xl hover:bg-black transition-all active:scale-95">
                    Register Hardware
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
