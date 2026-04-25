<x-guest-layout>
    <div class="space-y-6">
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('auth.full_name')" />
                <div class="relative group">
                    <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-white/20 group-focus-within:text-primary-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <x-text-input id="name" class="ps-11" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-500 text-xs font-bold" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('auth.email')" />
                <div class="relative group">
                    <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-white/20 group-focus-within:text-primary-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" /></svg>
                    </div>
                    <x-text-input id="email" class="ps-11" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="admin@example.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 text-xs font-bold" />
            </div>

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

            @if(request()->has('plan'))
                <input type="hidden" name="plan_id" value="{{ request('plan') }}">
            @endif

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
