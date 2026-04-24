@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="flex items-center gap-4">
        <a href="{{ route('users.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-primary-600 shadow-sm transition-all">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-heading font-bold text-gray-900">{{ __('users.add_user') }}</h2>
            <p class="text-gray-500 text-sm">{{ __('users.create_subtitle') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-100/50 border border-gray-100 p-8">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label class="text-xs font-black uppercase tracking-widest text-gray-400 ms-1">{{ __('users.name') }}</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                       class="w-full bg-gray-50 border-0 rounded-2xl px-6 py-4 text-gray-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium placeholder-gray-400"
                       placeholder="e.g. John Doe">
                @error('name')<p class="text-xs text-red-500 ms-1 italic">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-2">
                <label class="text-xs font-black uppercase tracking-widest text-gray-400 ms-1">{{ __('users.email') }}</label>
                <input type="email" name="email" required value="{{ old('email') }}"
                       class="w-full bg-gray-50 border-0 rounded-2xl px-6 py-4 text-gray-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium placeholder-gray-400"
                       placeholder="john@example.com">
                @error('email')<p class="text-xs text-red-500 ms-1 italic">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-gray-400 ms-1">{{ __('users.password') ?? 'Password' }}</label>
                    <input type="password" name="password" required
                           class="w-full bg-gray-50 border-0 rounded-2xl px-6 py-4 text-gray-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-gray-400 ms-1">{{ __('users.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full bg-gray-50 border-0 rounded-2xl px-6 py-4 text-gray-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium">
                </div>
            </div>
            @error('password')<p class="text-xs text-red-500 ms-1 italic">{{ $message }}</p>@enderror

            <div class="space-y-2">
                <label class="text-xs font-black uppercase tracking-widest text-gray-400 ms-1">{{ __('users.role') }}</label>
                <select name="role_id" required
                        class="w-full bg-gray-50 border-0 rounded-2xl px-6 py-4 text-gray-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium appearance-none">
                    <option value="">{{ __('users.select_role') }}</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')<p class="text-xs text-red-500 ms-1 italic">{{ $message }}</p>@enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-primary-600 rounded-2xl text-white font-black uppercase tracking-widest shadow-lg shadow-primary-100 hover:bg-primary-700 hover:shadow-primary-200 transition-all">
                    {{ __('users.add_user') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
