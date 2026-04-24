@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">

    {{-- Header --}}
    <div>
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 font-semibold mb-6 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('users.back_to_users') }}
        </a>
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-primary-100 flex items-center justify-center text-primary-700 font-black text-xl flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-3xl font-heading font-black text-gray-900">{{ __('users.edit_user') }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 space-y-6">

        @if($errors->any())
        <div class="px-5 py-4 bg-red-50 border border-red-200 rounded-2xl">
            <p class="text-sm font-bold text-red-600 mb-2">{{ __('messages.error') }}</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-500">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label for="name" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('users.name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full bg-gray-50 border-transparent rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all @error('name') ring-2 ring-red-400 @enderror">
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('users.email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full bg-gray-50 border-transparent rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all @error('email') ring-2 ring-red-400 @enderror">
            </div>

            {{-- Role --}}
            <div>
                <label for="role_id" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('users.role') }}</label>
                <select id="role_id" name="role_id" required
                        class="w-full bg-gray-50 border-transparent rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ (old('role_id', $user->role_id) == $role->id) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Password (optional on edit) --}}
            <div class="pt-2 border-t border-gray-50">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">{{ __('users.change_password_hint') }}</p>
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('messages.password') }}</label>
                        <input type="password" id="password" name="password" autocomplete="new-password"
                               placeholder="{{ __('users.leave_blank') }}"
                               class="w-full bg-gray-50 border-transparent rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all @error('password') ring-2 ring-red-400 @enderror">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('users.confirm_password') }}</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                               class="w-full bg-gray-50 border-transparent rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all">
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-4 pt-4 border-t border-gray-50">
                <a href="{{ route('users.index') }}"
                   class="flex-1 py-4 text-center rounded-2xl bg-gray-100 font-bold text-gray-500 hover:bg-gray-200 transition-all">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit"
                        class="flex-1 py-4 rounded-2xl bg-gray-900 font-black text-white hover:bg-black shadow-xl hover:shadow-gray-200 active:scale-95 transition-all">
                    {{ __('messages.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
