@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-12rem)] flex flex-col lg:flex-row gap-8" x-data="posSystem()">
    
    <!-- Left Side: Product Catalog -->
    <div class="flex-1 flex flex-col min-h-0 space-y-6">
        <div class="flex items-center justify-between bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('pos.title') }}</h2>
                <p class="text-gray-500 text-sm">{{ __('pos.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" x-model="search" placeholder="{{ __('messages.search') }}" class="bg-gray-50 border-gray-100 rounded-xl ps-10 pe-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 w-64">
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1 overflow-y-auto pe-2 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($products as $product)
            <div @click="addToCart({{ json_encode($product) }})"
                 x-show="'{{ strtolower($product->name) }}'.includes(search.toLowerCase())"
                 class="bg-white p-6 rounded-[2rem] shadow-sm border border-transparent hover:border-primary-500 hover:shadow-xl hover:shadow-primary-50 transition-all cursor-pointer group relative">
                
                <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-primary-50 group-hover:text-primary-600 transition-all mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                
                <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $product->name }}</h3>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-xl font-heading font-black text-gray-900">${{ number_format($product->price, 2) }}</span>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $product->category->name ?? 'Items' }}</span>
                </div>
                
                <div class="absolute top-4 right-4 bg-primary-100 text-primary-700 text-[10px] font-black px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                    {{ __('messages.view') }}
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[2rem] border border-gray-100 border-dashed">
                <p class="text-gray-400 font-medium italic">{{ __('pos.empty_cart') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Right Side: Order Panel -->
    <div class="w-full lg:w-[420px] bg-white rounded-[2.5rem] shadow-2xl flex flex-col border border-gray-100">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-heading font-black text-gray-900">{{ __('pos.cart') }}</h3>
                <p class="text-xs text-gray-400 uppercase font-black tracking-widest mt-1">Cashier: {{ auth()->user()->name ?? 'Staff' }}</p>
            </div>
            <button @click="cart = []" x-show="cart.length > 0" class="text-xs font-black text-red-500 uppercase hover:underline">{{ __('messages.cancel') }}</button>
        </div>

        <!-- Order Items Scroll Area -->
        <div class="flex-1 overflow-y-auto p-8 space-y-6">
            <template x-if="cart.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-center space-y-4">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-200">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <p class="text-gray-400 font-medium tracking-tight">{{ __('pos.empty_cart') }}</p>
                </div>
            </template>

            <template x-for="(item, index) in cart" :key="item.id">
                <div class="flex items-center gap-4 group animate-in slide-in-from-right-4 duration-300">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 truncate" x-text="item.name"></p>
                        <p class="text-xs text-primary-600 font-bold" x-text="'$' + item.price.toFixed(2)"></p>
                    </div>
                    
                    <div class="flex items-center bg-gray-50 rounded-xl p-1 gap-1">
                        <button @click="updateQty(index, -1)" class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center hover:bg-gray-100 text-gray-600 transition-colors">-</button>
                        <span class="w-8 text-center font-black text-gray-900 text-sm" x-text="item.qty"></span>
                        <button @click="updateQty(index, 1)" class="w-8 h-8 rounded-lg bg-primary-600 shadow-lg shadow-primary-100 flex items-center justify-center hover:bg-primary-700 text-white transition-colors">+</button>
                    </div>
                    
                    <p class="w-16 text-right font-heading font-black text-gray-900" x-text="'$' + (item.price * item.qty).toFixed(2)"></p>
                </div>
            </template>
        </div>

        <!-- Total & Final Checkout Form -->
        <div class="p-8 bg-gray-50 rounded-b-[2.5rem] border-t border-gray-100 space-y-6">
            <div class="flex justify-between items-end pt-2">
                <span class="text-gray-900 font-black uppercase text-xs tracking-widest">{{ __('pos.total') }}</span>
                <span class="text-4xl font-heading font-black text-primary-600" x-text="'$' + total.toFixed(2)"></span>
            </div>

            <form action="{{ route('pos.orders.store') }}" method="POST" @submit.prevent="checkout()">
                @csrf
                <input type="hidden" name="cart_data" :value="JSON.stringify(cart)">
                <input type="hidden" name="total_amount" :value="total">
                
                <button type="submit" 
                        :disabled="cart.length === 0"
                        class="w-full bg-gray-900 text-white font-black py-5 rounded-2xl shadow-xl hover:bg-black transition-all active:scale-95 disabled:opacity-50 disabled:grayscale">
                    {{ __('pos.checkout') }}
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function posSystem() {
        return {
            search: '',
            cart: [],
            
            get total() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
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
                this.addToast(`Added ${product.name} to cart`);
            },

            updateQty(index, delta) {
                this.cart[index].qty += delta;
                if (this.cart[index].qty <= 0) {
                    this.cart.splice(index, 1);
                }
            },

            checkout() {
                this.askConfirm(
                    'Complete Order?',
                    `Total amount: $${this.total.toFixed(2)}. Proceed to finalize payment?`,
                    () => {
                        this.$el.closest('form').submit();
                    }
                );
            }
        }
    }
</script>
@endsection
