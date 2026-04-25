@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="{ editingCategory: null, categoryName: '' }">
    <!-- Page Header -->
    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('pos.categories_title') }}</h2>
            <p class="text-gray-500 text-sm">{{ __('pos.categories_subtitle') }}</p>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('products.index') }}" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition-colors">
                {{ __('pos.back_to_products') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Category Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 space-y-6">
                <h3 class="text-lg font-heading font-bold text-gray-900">{{ __('pos.add_category') }}</h3>
                
                <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('pos.category_name') }}</label>
                        <input type="text" name="name" required
                               class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 transition-all font-bold" 
                               placeholder="{{ __('pos.category_name') }}">
                    </div>
                    <button type="submit" class="w-full py-5 rounded-2xl bg-gray-900 font-black text-white shadow-xl hover:bg-black transition-all active:scale-95">
                        {{ __('messages.save') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Categories List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.name') ?? 'Name' }}</th>
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('pos.products') }}</th>
                            <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <div x-show="editingCategory !== {{ $category->id }}" class="font-bold text-gray-900">{{ $category->name }}</div>
                                <form x-show="editingCategory === {{ $category->id }}" action="{{ route('categories.update', $category) }}" method="POST" class="flex gap-2">
                                    @csrf @method('PUT')
                                    <input type="text" name="name" value="{{ $category->name }}" class="bg-white border-primary-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 font-bold">
                                    <button type="submit" class="bg-primary-600 text-white p-2 rounded-xl hover:bg-primary-700">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </button>
                                    <button type="button" @click="editingCategory = null" class="bg-gray-100 text-gray-400 p-2 rounded-xl hover:bg-gray-200">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </form>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest">
                                    {{ $category->products_count }} items
                                </span>
                            </td>
                            <td class="px-8 py-6 text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="editingCategory = {{ $category->id }}" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z" /></svg>
                                    </button>
                                    
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" 
                                          @submit.prevent="askConfirm('{{ __('messages.delete') }}', '{{ __('pos.category_delete_confirm', ['name' => $category->name]) }}', () => $el.submit())">
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
                                <p class="text-sm text-gray-400 italic">No categories created yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
