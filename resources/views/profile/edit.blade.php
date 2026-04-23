@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="p-2 bg-white rounded-xl border border-gray-100 text-gray-400 hover:text-gray-900 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('settings.profile') }}</h2>
            <p class="text-gray-500 text-sm">{{ __('settings.account_subtitle') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm text-center">
                <div class="w-24 h-24 rounded-[2rem] bg-primary-600 flex items-center justify-center text-white text-4xl font-heading font-black shadow-2xl shadow-primary-100 mx-auto mb-6">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <h3 class="text-xl font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                <p class="text-sm text-gray-400 mb-6">{{ auth()->user()->email }}</p>
                
                <div class="pt-6 border-t border-gray-50 flex justify-center gap-4">
                    <span class="px-3 py-1 bg-primary-50 text-[10px] font-black text-primary-600 uppercase rounded-full">Administrator</span>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm">
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('settings.full_name') }}</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all font-bold text-gray-900 @error('name') ring-2 ring-red-500 @enderror">
                            @error('name') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('settings.email') }}</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all font-bold text-gray-900 @error('email') ring-2 ring-red-500 @enderror">
                            @error('email') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-50">
                        <button type="submit" class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-black shadow-xl hover:bg-black transition-all active:scale-95">
                            {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
