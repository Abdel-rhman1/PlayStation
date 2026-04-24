@extends('layouts.guest')

@section('content')
<div class="relative w-full max-w-md">
    
    {{-- Header --}}
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 text-primary-600 mb-6">
            <svg class="w-8 h-8 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        </div>
        <h2 class="text-3xl font-heading font-black text-white tracking-tight">Join the Team</h2>
        <p class="mt-3 text-gray-400 font-medium">Create your account to accept the invitation.</p>
    </div>

    {{-- Form --}}
    <div class="relative rounded-[2.5rem] p-1 bg-white/5 border border-white/10 shadow-2xl backdrop-blur-xl">
        <div class="relative bg-gray-900/50 rounded-[2.25rem] p-8 backdrop-blur-md">
            
            <form method="POST" action="{{ route('invitations.process', $invitation->token) }}" class="space-y-6">
                @csrf

                {{-- Name --}}
                <div class="space-y-2">
                    <label for="name" class="block text-[11px] font-black tracking-widest uppercase text-gray-400 pl-2">Full Name</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none text-gray-500 group-focus-within:text-primary-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="block w-full bg-white/5 border border-white/10 text-white rounded-2xl ps-12 p-4 text-sm font-bold focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder-gray-500 @error('name') border-red-500 focus:ring-red-500 @enderror"
                            placeholder="John Doe">
                    </div>
                    @error('name')
                        <p class="text-xs font-bold text-red-400 pl-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email (Readonly) --}}
                <div class="space-y-2">
                    <label for="email" class="block text-[11px] font-black tracking-widest uppercase text-gray-400 pl-2">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none text-gray-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input id="email" type="email" value="{{ $invitation->email }}" readonly disabled
                            class="block w-full bg-white/5 border border-white/10 text-gray-500 rounded-2xl ps-12 p-4 text-sm font-bold opacity-50 cursor-not-allowed">
                    </div>
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label for="password" class="block text-[11px] font-black tracking-widest uppercase text-gray-400 pl-2">Set Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none text-gray-500 group-focus-within:text-primary-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="block w-full bg-white/5 border border-white/10 text-white rounded-2xl ps-12 p-4 text-sm font-bold focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder-gray-500 @error('password') border-red-500 focus:ring-red-500 @enderror"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="text-xs font-bold text-red-400 pl-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-[11px] font-black tracking-widest uppercase text-gray-400 pl-2">Confirm Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none text-gray-500 group-focus-within:text-primary-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                            class="block w-full bg-white/5 border border-white/10 text-white rounded-2xl ps-12 p-4 text-sm font-bold focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder-gray-500"
                            placeholder="••••••••">
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="w-full relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-primary-600 to-purple-600 rounded-2xl opacity-70 group-hover:opacity-100 transition duration-200 blur"></div>
                    <div class="relative flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-400 text-white w-full py-4 rounded-2xl font-black uppercase text-sm tracking-widest transition-all">
                        <span>Join Team</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </div>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
