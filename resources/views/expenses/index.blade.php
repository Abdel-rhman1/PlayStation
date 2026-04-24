@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Page Header & Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('expenses.title') }}</h2>
            <p class="text-gray-500 text-sm">{{ __('expenses.subtitle') }}</p>
        </div>
        
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="lg:col-span-3 bg-green-50 border border-green-100 p-6 rounded-3xl flex items-center gap-4 text-green-700 animate-in fade-in zoom-in duration-300">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Add Expense Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 space-y-6 sticky top-28">
                <h3 class="text-lg font-heading font-bold text-gray-900">{{ __('expenses.add_expense') }}</h3>
                
                <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('expenses.amount') }} ({{ __('messages.currency_symbol') }})</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}"
                               class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 transition-all font-bold @error('amount') border-red-300 @enderror" 
                               placeholder="0.00">
                        @error('amount') <p class="text-[10px] font-bold text-red-500 uppercase tracking-wide">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('expenses.type') }}</label>
                        <select name="type" class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 transition-all font-bold appearance-none @error('type') border-red-300 @enderror">
                            <option value="maintenance" {{ old('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="rent" {{ old('type') == 'rent' ? 'selected' : '' }}>Rent & Utilities</option>
                            <option value="salary" {{ old('type') == 'salary' ? 'selected' : '' }}>Salary</option>
                            <option value="marketing" {{ old('type') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="hardware" {{ old('type') == 'hardware' ? 'selected' : '' }}>Hardware / Games</option>
                        </select>
                        @error('type') <p class="text-[10px] font-bold text-red-500 uppercase tracking-wide">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('expenses.date') }}</label>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}"
                               class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 transition-all font-bold @error('date') border-red-300 @enderror">
                        @error('date') <p class="text-[10px] font-bold text-red-500 uppercase tracking-wide">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="w-full py-5 rounded-2xl bg-gray-900 font-black text-white shadow-xl hover:bg-black transition-all active:scale-95">
                        {{ __('messages.save') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Expenses History -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('expenses.date') }}</th>
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('expenses.type') }}</th>
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">{{ __('expenses.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-gray-400 font-mono">{{ $expense->date->format('Y-m-d') }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <span class="w-2 h-2 rounded-full bg-primary-400"></span>
                                    <span class="font-bold text-gray-900 uppercase text-xs tracking-wider">{{ $expense->type }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right font-heading font-black text-gray-900">
                                {{ __('messages.currency_symbol') }} {{ number_format($expense->amount, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-8 py-20 text-center">
                                <div class="max-w-xs mx-auto space-y-4">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mx-auto">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z" /></svg>
                                    </div>
                                    <p class="text-sm text-gray-400 italic">No expenses recorded for this period.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-4">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
