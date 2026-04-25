<x-admin-guest-layout>
    <div class="space-y-6">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('auth.email')" />
                <div class="relative group">
                    <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-white/20 group-focus-within:text-red-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" /></svg>
                    </div>
                    <x-text-input id="email" class="ps-11 border-white/10 bg-white/5 focus:border-red-500 focus:ring-red-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="admin@system.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 text-xs font-bold" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('auth.password')" />
                <div class="relative group">
                    <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-white/20 group-focus-within:text-red-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <x-text-input id="password" class="ps-11 border-white/10 bg-white/5 focus:border-red-500 focus:ring-red-500" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500 text-xs font-bold" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <label for="remember_me" class="relative flex items-center cursor-pointer group">
                    <input id="remember_me" type="checkbox" name="remember" class="peer sr-only">
                    <div class="w-5 h-5 border-2 border-white/10 rounded-lg group-hover:border-red-500/50 transition-all peer-checked:bg-red-600 peer-checked:border-red-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <span class="ms-3 text-xs font-bold text-white/40 uppercase tracking-widest group-hover:text-white/60 transition-colors">{{ __('auth.stay_signed_in') }}</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex items-center justify-center gap-3 py-4 rounded-2xl bg-red-600 font-black text-white shadow-xl shadow-red-900/20 hover:bg-red-700 transition-all active:scale-95 group">
                    {{ __('auth.sign_in') }}
                    <svg class="w-5 h-5 text-white/70 group-hover:translate-x-1 transition-transform rtl:rotate-180 rtl:group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>
</x-admin-guest-layout>
