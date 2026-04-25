<x-guest-layout>
    <div class="space-y-6">
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Shop Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="shop_name" :value="__('auth.shop_name')" />
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-white/20 group-focus-within:text-primary-500 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <x-text-input id="shop_name" class="ps-11" type="text" name="shop_name" :value="old('shop_name')" required autofocus placeholder="Galaxy Gaming Lounge" />
                    </div>
                    <x-input-error :messages="$errors->get('shop_name')" class="mt-2 text-rose-500 text-xs font-bold" />
                </div>

                <div>
                    <x-input-label for="shop_slug" :value="__('auth.shop_subdomain') . ' (' . __('admin.expiry_date_optional') . ')'" />
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-white/20 group-focus-within:text-primary-500 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                        </div>
                        <x-text-input id="shop_slug" class="ps-11" type="text" name="shop_slug" :value="old('shop_slug')" placeholder="galaxy-gaming" />
                    </div>
                    <x-input-error :messages="$errors->get('shop_slug')" class="mt-2 text-rose-500 text-xs font-bold" />
                </div>
            </div>

            <!-- Name -->
            <div class="mt-4">
                <x-input-label for="name" :value="__('auth.full_name')" />
                <div class="relative group">
                    <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-white/20 group-focus-within:text-primary-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <x-text-input id="name" class="ps-11" type="text" name="name" :value="old('name')" required autocomplete="name" placeholder="John Doe" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-500 text-xs font-bold" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('auth.email')" />
                <div class="relative group">
                    <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-white/20 group-focus-within:text-primary-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" /></svg>
                    </div>
                    <x-text-input id="email" class="ps-11" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="admin@example.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 text-xs font-bold" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('auth.password')" />
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-white/20 group-focus-within:text-primary-500 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <x-text-input id="password" class="ps-11" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500 text-xs font-bold" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('auth.confirm_password')" />
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-white/20 group-focus-within:text-primary-500 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                        <x-text-input id="password_confirmation" class="ps-11" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-rose-500 text-xs font-bold" />
                </div>
            </div>

            <!-- Subscription Plan -->
            <div class="mt-4">
                <x-input-label for="plan_id" :value="__('auth.choose_plan')" />
                <div class="relative group">
                    <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-white/20 group-focus-within:text-primary-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                    </div>
                    <select id="plan_id" name="plan_id" required class="block w-full ps-11 pe-10 py-4 bg-white/5 border border-white/10 rounded-2xl text-white text-sm font-bold focus:border-primary-500 focus:ring-primary-500 selection:bg-primary-500 appearance-none transition-all">
                        @php $plans = \App\Models\Plan::all(); @endphp
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }} class="bg-slate-900 text-white">
                                {{ $plan->name }} ({{ $plan->price }} {{ __('landing.currency_symbol') }}/mo)
                            </option>
                        @endforeach
                    </select>
                </div>
                <x-input-error :messages="$errors->get('plan_id')" class="mt-2 text-rose-500 text-xs font-bold" />
            </div>

            <div class="pt-4">
                <x-primary-button>
                    <span class="flex items-center">
                        {{ __('auth.create_account') }}
                        <svg class="ms-3 w-5 h-5 text-white/70 group-hover:translate-x-1 transition-transform rtl:rotate-180 rtl:group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </span>
                </x-primary-button>
            </div>

            <div class="text-center pt-2">
                <p class="text-white/30 text-xs font-bold tracking-widest uppercase">
                    {{ __('auth.already_registered') }}
                    <a href="{{ route('login') }}" class="text-primary-500 hover:text-primary-400 transition-colors ms-1 uppercase">{{ __('auth.sign_in') }}</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
