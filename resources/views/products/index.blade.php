@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="{ modalOpen: false, editMode: false, activeProductId: null, formData: { name: '', price: 0, stock: 0, category_id: '' } }">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('products.title') }}</h2>
            <p class="text-gray-500 text-sm">{{ __('products.subtitle') }}</p>
        </div>
        
        <button @click="modalOpen = true; editMode = false" 
                class="inline-flex items-center px-6 py-3 bg-primary-600 rounded-2xl text-sm font-bold text-white shadow-xl shadow-primary-100 hover:bg-primary-700 transition-all active:scale-95">
            <svg class="w-5 h-5 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('products.add_product') }}
        </button>
    </div>

    <!-- Product Table -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('products.product_info') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('products.category') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('products.pricing') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('products.stock_level') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">{{ __('products.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <span class="font-bold text-gray-900">{{ $product->name }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full bg-primary-50 text-primary-600 text-[10px] font-black uppercase tracking-widest">
                                {{ $product->category->name ?? __('products.uncategorized') }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-heading font-black text-gray-900">${{ number_format($product->price, 2) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold {{ $product->stock < 10 ? 'text-red-500' : 'text-gray-600' }}">
                                    {{ $product->stock }}
                                </span>
                                @if($product->stock < 10)
                                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6 text-end flex justify-end gap-2">
                            <button @click="modalOpen = true; editMode = true; activeProductId = {{ $product->id }}; 
                                            formData = { name: '{{ $product->name }}', price: {{ $product->price }}, stock: {{ $product->stock }}, category_id: {{ $product->category_id }} }" 
                                    class="p-2 text-gray-400 hover:text-primary-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        @click.prevent="askConfirm('{{ __('products.delete_confirm_title') }}', '{{ __('products.delete_confirm_msg') }}', () => $el.closest('form').submit())"
                                        class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Modal Overlay -->
    <div x-show="modalOpen" 
         x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden"
             @click.away="modalOpen = false"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="scale-95 translate-y-4"
             x-transition:enter-end="scale-100 translate-y-0">
            
            <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-xl font-heading font-black text-gray-900" x-text="editMode ? '{{ __('products.edit_product') }}' : '{{ __('products.add_product') }}'"></h3>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form :action="editMode ? '{{ route('products.update', ':id') }}'.replace(':id', activeProductId) : '{{ route('products.store') }}'" 
                  method="POST" 
                  class="p-8 space-y-6">
                @csrf
                <template x-if="editMode">
                    @method('PUT')
                </template>

                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('products.product_name') }}</label>
                    <input type="text" name="name" x-model="formData.name" class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 transition-all font-bold" placeholder="e.g. Redbull Blue Edition">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('products.price') }} ($)</label>
                        <input type="number" step="0.01" name="price" x-model="formData.price" class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 transition-all font-bold" value="0.00">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('products.stock_level') }}</label>
                        <input type="number" name="stock" x-model="formData.stock" class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 transition-all font-bold" value="0">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('products.category') }}</label>
                    <select name="category_id" x-model="formData.category_id" class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 transition-all font-bold appearance-none">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" @click="modalOpen = false" 
                            class="flex-1 px-6 py-4 rounded-2xl bg-gray-100 text-gray-500 font-bold hover:bg-gray-200 transition-all">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-4 rounded-2xl bg-primary-600 text-white font-bold shadow-lg shadow-primary-100 hover:bg-primary-700 transition-all">
                        {{ __('products.save_product') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
