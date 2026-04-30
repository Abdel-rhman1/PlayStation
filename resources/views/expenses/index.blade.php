@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Page Header & Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('expenses.title') }}</h2>
                <p class="text-gray-500 text-sm">{{ __('expenses.subtitle') }}</p>
            </div>
            <a href="{{ route('expenses.categories.index') }}" class="bg-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-gray-900 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                {{ __('expenses.manage_categories') }}
            </a>
        </div>
        
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="lg:col-span-3 bg-green-50 border border-green-100 p-6 rounded-3xl flex items-center gap-4 text-green-700 animate-in fade-in zoom-in duration-300">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" x-data="{ editingExpense: null }">
        <!-- Add Expense Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 space-y-6 sticky top-28">
                <h3 class="text-lg font-heading font-bold text-gray-900">{{ __('expenses.add_expense') }}</h3>
                
                <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('expenses.amount') }} ({{ __('messages.currency_symbol') }})</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required
                               class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 transition-all font-bold @error('amount') border-red-300 @enderror" 
                               placeholder="0.00">
                        @error('amount') <p class="text-[10px] font-bold text-red-500 uppercase tracking-wide">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Category</label>
                        <select name="expense_category_id" required class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 transition-all font-bold appearance-none @error('expense_category_id') border-red-300 @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('expense_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('expense_category_id') <p class="text-[10px] font-bold text-red-500 uppercase tracking-wide">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</label>
                        <select name="type" required class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 transition-all font-bold appearance-none @error('type') border-red-300 @enderror">
                            <option value="">Select Type</option>
                            @foreach(\App\Enums\ExpenseType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>{{ ucfirst($type->name) }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="text-[10px] font-bold text-red-500 uppercase tracking-wide">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}"
                               class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 transition-all font-bold" 
                               placeholder="What was this for?">
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
                    @if($categories->count() == 0)
                        <p class="text-[10px] text-amber-600 font-bold text-center">Please add categories first!</p>
                    @endif
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
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">Category / Desc</th>
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">{{ __('expenses.amount') }}</th>
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-gray-400 font-mono">{{ $expense->date->format('Y-m-d') }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-3">
                                        <span class="w-2 h-2 rounded-full bg-primary-400"></span>
                                        <span class="font-bold text-gray-900 uppercase text-xs tracking-wider">{{ $expense->category?->name ?? 'Uncategorized' }}</span>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1">{{ $expense->description ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right font-heading font-black text-gray-900">
                                {{ __('messages.currency_symbol') }} {{ number_format($expense->amount, 2) }}
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline"
                                          @submit.prevent="askConfirm('{{ __('messages.delete') }}', '{{ __('expenses.delete_confirm', ['amount' => $expense->amount]) }}', () => $el.submit())">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
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
