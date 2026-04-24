@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-8rem)] flex flex-col lg:flex-row gap-4 p-2 lg:p-0" x-data="posSystem()" x-cloak>
    
    <!-- Product Catalog Section -->
    <div class="flex-1 flex flex-col min-h-0 container mx-auto">
        <!-- Compact Search & Filter -->
        <div class="bg-white px-4 py-3 rounded-2xl border border-gray-100 flex flex-wrap items-center gap-3 mb-4">
            <div class="flex-1 relative min-w-[200px]">
                <input type="text" x-model="search" placeholder="{{ __('pos.search_product') }}" 
                       class="w-full bg-gray-50 border-transparent focus:ring-primary-500 rounded-xl text-sm py-2 ps-9">
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            
            <div class="flex gap-1 overflow-x-auto no-scrollbar py-1">
                <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-4 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-colors">{{ __('pos.all') }}</button>
                @php $uniqueCategories = $products->pluck('category.name')->unique()->filter(); @endphp
                @foreach($uniqueCategories as $cat)
                <button @click="activeCategory = '{{ $cat }}'" :class="activeCategory === '{{ $cat }}' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-4 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-colors">{{ $cat }}</button>
                @endforeach
            </div>
        </div>

        <!-- Responsive Simple Grid -->
        <div class="flex-1 overflow-y-auto scroll-smooth pb-20 lg:pb-0">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">
                @foreach($products as $product)
                <div @click="addToCart({{ json_encode($product) }})"
                     x-show="shouldShow('{{ strtolower($product->name) }}', '{{ $product->category->name ?? 'Items' }}')"
                     class="bg-white p-3 rounded-xl border border-gray-100 hover:border-primary-500 hover:shadow-md transition-all cursor-pointer flex flex-col justify-between active:scale-95">
                    <div>
                        <div class="aspect-square bg-gray-50 rounded-lg flex items-center justify-center mb-2 overflow-hidden">
                             <svg class="w-8 h-8 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </div>
                        <h3 class="text-xs font-bold text-gray-800 line-clamp-2 leading-tight">{{ $product->name }}</h3>
                        <p class="text-[10px] text-gray-400 mt-1 font-medium">{{ $product->category->name ?? __('pos.general') }}</p>
                    </div>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-sm font-black text-primary-600">{{ __('messages.currency_symbol') }} {{ number_format($product->price, 2) }}</span>
                        <div class="w-6 h-6 bg-gray-100 rounded-md flex items-center justify-center text-gray-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Side: Simplified Cart (Mobile Bottom Sheet / Desktop Sidebar) -->
    <div class="fixed inset-x-0 bottom-0 z-40 lg:static lg:w-96 lg:h-full bg-white lg:bg-transparent shadow-2xl lg:shadow-none transition-transform duration-300"
         :class="showCart ? 'translate-y-0' : 'translate-y-[calc(100%-80px)] lg:translate-y-0'">
        
        <div class="bg-white h-full lg:rounded-3xl border-t lg:border border-gray-100 flex flex-col overflow-hidden max-h-[80vh] lg:max-h-full">
            <!-- Mobile Toggle -->
            <div @click="showCart = !showCart" class="lg:hidden h-20 flex items-center justify-between px-6 border-b border-gray-50 cursor-pointer">
                <div class="flex items-center gap-2">
                    <span class="bg-primary-600 text-white text-[10px] font-black px-2 py-0.5 rounded-full" x-text="cart.length"></span>
                    <h3 class="font-black text-gray-900 text-sm">{{ __('pos.review_cart') }}</h3>
                </div>
                <div class="text-right">
                    <p class="text-[10px] uppercase font-black text-gray-400 leading-none">{{ __('pos.total') }}</p>
                    <p class="text-lg font-black text-primary-600" x-text="'{{ __('messages.currency_symbol') }} ' + total.toFixed(2)"></p>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden lg:flex p-6 border-b border-gray-50 items-center justify-between bg-gray-50/50">
                <h3 class="font-black text-gray-900 text-sm tracking-tight uppercase">{{ __('pos.current_order') }}</h3>
                <button @click="cart = []" x-show="cart.length > 0" class="text-[10px] font-black text-red-500 hover:underline">{{ __('pos.clear_items') }}</button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-3">
                <template x-if="cart.length === 0">
                    <div class="h-full flex flex-col items-center justify-center opacity-40 py-10">
                        <p class="text-xs font-bold text-gray-400">{{ __('pos.empty_cart') }}</p>
                    </div>
                </template>

                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-50 hover:bg-gray-50 transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-800 truncate" x-text="item.name"></p>
                            <p class="text-[10px] font-black text-primary-500" x-text="'{{ __('messages.currency_symbol') }} ' + item.price.toFixed(2)"></p>
                        </div>
                        <div class="flex items-center gap-2 bg-white rounded-lg p-0.5 border border-gray-100">
                            <button @click="updateQty(index, -1)" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-red-500">-</button>
                            <span class="text-[10px] font-black w-4 text-center" x-text="item.qty"></span>
                            <button @click="updateQty(index, 1)" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-primary-600">+</button>
                        </div>
                        <div class="text-right w-14">
                            <p class="text-xs font-black text-gray-900" x-text="'{{ __('messages.currency_symbol') }} ' + (item.price * item.qty).toFixed(2)"></p>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-6 bg-gray-50 space-y-4">
                <!-- Device Picker -->
                <div class="relative">
                    <select x-model="selectedDevice" class="w-full bg-white border-gray-200 rounded-xl px-4 py-3 text-xs font-bold text-gray-900 focus:ring-primary-500 focus:border-primary-500 shadow-sm appearance-none">
                        <option value="" disabled selected>{{ __('pos.link_session') }}</option>
                        @foreach($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 end-0 pe-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <div class="flex justify-between items-center px-1">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('pos.total_amount') }}</span>
                    <span class="text-2xl font-black text-primary-600" x-text="'{{ __('messages.currency_symbol') }} ' + total.toFixed(2)"></span>
                </div>

                <form action="{{ route('pos.orders.store') }}" method="POST" @submit.prevent="checkout()">
                    @csrf
                    <input type="hidden" name="cart_data" :value="JSON.stringify(cart)">
                    <input type="hidden" name="total_amount" :value="total">
                    <input type="hidden" name="device_id" :value="selectedDevice">
                    <button type="submit" :disabled="cart.length === 0" class="w-full bg-primary-600 text-white font-black py-4 rounded-xl shadow-lg hover:bg-primary-700 active:scale-95 transition-all disabled:opacity-50">
                        {{ __('pos.process_order') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    [x-cloak] { display: none !important; }
</style>

<script>
    function posSystem() {
        return {
            search: '',
            cart: [],
            selectedDevice: '',
            activeCategory: 'all',
            showCart: false,
            
            get total() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            },

            shouldShow(name, category) {
                return name.includes(this.search.toLowerCase()) && 
                       (this.activeCategory === 'all' || category === this.activeCategory);
            },

            addToCart(product) {
                const existing = this.cart.find(item => item.id === product.id);
                if (existing) {
                    existing.qty++;
                } else {
                    this.cart.push({
                        id: product.id,
                        name: product.name,
                        price: parseFloat(product.price),
                        qty: 1
                    });
                }
                this.addToast(`{{ __('pos.added_toast') }} ${product.name}`);
                if (!this.showCart && window.innerWidth < 1024) this.showCart = true;
            },

            updateQty(index, delta) {
                this.cart[index].qty += delta;
                if (this.cart[index].qty <= 0) this.cart.splice(index, 1);
            },

            checkout() {
                if (!this.selectedDevice) {
                    this.addToast('{{ __('pos.device_required_toast') }}', 'error');
                    return;
                }
                this.askConfirm('{{ __('pos.process_confirm_toast') }}', `{{ __('pos.total') }}: {{ __('messages.currency_symbol') }} ${this.total.toFixed(2)}`, () => {
                    this.$el.closest('form').submit();
                });
            }
        }
    }
</script>
@endsection
